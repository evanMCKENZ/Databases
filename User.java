import java.sql.*;
import java.util.Scanner;
import java.io.UnsupportedEncodingException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class User {

	static int debug = 0; // command line argument 'debug' will set this to be 1

	Connection conn = null;
	Statement stmt = null;
	String sqlstr = null;
	ResultSet rs = null;
	Boolean  loggedIn = false;

	private void printException(SQLException ex) {
		System.out.println("SQLException: " + ex.getMessage());
		System.out.println("SQLState: " + ex.getSQLState());
		System.out.println("VendorError: " + ex.getErrorCode());
		ex.printStackTrace();
	}

	/**
	 * @return 
	 * 		1: A database connection has been established
	 * 		0: failed to connection to database server
	 */
	public int connectDB() {
		
		// The connection to database existed already
		if (conn!=null) return 1;
		
		try {
			conn = DriverManager.getConnection("jdbc:mysql://classdb.it.mtu.edu/ecmckenz", "ecmckenz", "Password27!");
			debugPrint("Connected to the database!");
		} catch (SQLException e) {
			printException(e);
			return 1;
		}
		return 0;
	}

	/**
	 * Close the open database connection. 
	 */
	public void disconnect() {
		
		// The connection is not open, nothing to close
		if (conn==null) return;
		
		try {
			conn.close();
			debugPrint("Disconnected from database!");
		} catch (SQLException e) {
			printException(e);
		}
	}

	/**
	 * Check user name, password and status. If the user name and password are
	 * correct, and the user is not already logged in, then update the status and
	 * logging activity records.
	 * 
	 * @param user_name
	 * @param password
	 * @return -1: if user logged in from somewhere else already. 
	 * 			-2: user name or password is wrong. 
	 *         -3: SQL error. 
	 *         -4: no connection yet. 
	 *         -5: already logged in. 
	 *         0: correct username and password.
	 *        
	 * @throws NoSuchAlgorithmException
	 * @throws UnsupportedEncodingException
	 */
	public int login(String user_name, String password) throws NoSuchAlgorithmException, UnsupportedEncodingException {
		
		PreparedStatement smmt = null;
		if (conn==null) return -4;
		if (loggedIn) return -5;
			
		try{
			conn.setAutoCommit(false);
			conn.setTransactionIsolation(conn.TRANSACTION_SERIALIZABLE);
		}
		catch(SQLException e){
			e.printStackTrace();
		}

		try {
			
			// Generate the hash value for the password using sha-256 algorithm
			String hashed_password = digest(password);

			// Find the log in status for the given user name and password. 
			sqlstr = "select loggedIn from exam_user where user = ? and password = ?";
			smmt = conn.prepareStatement(sqlstr);
			smmt.setString(1, user_name);
			smmt.setString(2, hashed_password);
			debugPrint(sqlstr);
			rs = smmt.executeQuery();
			
			//Read the row from the result
			boolean ret = rs.next();

			//If there is no row, it means that user name or password is wrong.
			if (!ret) {
				return -2;
			}

			//Get the login status
			boolean loggedInAlready = rs.getBoolean(1);

			// If the user is already logged in from somewhere else, then user can't login again. 
			if (loggedInAlready) {
				return -1;
			}

			//Update the login status to be loggedIn
			sqlstr = "update exam_user set loggedIn = true where user= ?";
			smmt = conn.prepareStatement(sqlstr);
			smmt.setString(1, user_name);
			debugPrint(sqlstr);
			smmt.executeUpdate();

			//Set the status in this app to be logged in. 
			loggedIn = true; 
			
			conn.commit();

		} catch (SQLException e) {
			printException(e);
			return -3;
		}
		return 0;

	}

	/**
	 * @param password
	 * @return the hash value for password using sha_256 algorithm
	 * @throws NoSuchAlgorithmException
	 * @throws UnsupportedEncodingException
	 */
	private String digest(String password) throws NoSuchAlgorithmException, UnsupportedEncodingException {
		String s = "";

		MessageDigest sha256;
		sha256 = MessageDigest.getInstance("SHA-256");
		byte[] hash = sha256.digest(password.getBytes("UTF-8"));

		for (byte b : hash) {
			s = s + String.format("%02x", b);
		}
		return s;
	}

	/**
	 * log out the current user - set the loggedIn to be false.
	 * @param user_name
	 */
	public void logout(String user_name) {
		if (loggedIn!= true) return;
		
		try {
			stmt = conn.createStatement();
			// Update the login status to be logged out
			sqlstr = "update exam_user set loggedIn = false where user ='" + user_name + "'";
			debugPrint(sqlstr);
			stmt.executeUpdate(sqlstr);
		} catch (SQLException e) {
			printException(e);
		}
		return;
	}

	/**
	 * print the user login statistics
	 */
	public void stats() {

		try {

			System.out.println("");
			System.out.println("******* User login statistics data ******* ");

			stmt = conn.createStatement();
			sqlstr = "call stat() ";
			debugPrint(sqlstr);
			rs = stmt.executeQuery(sqlstr);

			// Retrieving the list of column names
			ResultSetMetaData rsMetaData = rs.getMetaData();
			System.out.format("%-10s%10s%20s\n", rsMetaData.getColumnName(1), rsMetaData.getColumnName(2),
					rsMetaData.getColumnName(3));

			// display the results
			while (rs.next()) {
				System.out.format("%10s%10d%20s\n", rs.getString(1), rs.getInt(2), rs.getTime(3));
			}

		} catch (SQLException e) {
			printException(e);
		}

	}

	private void debugPrint(String s) {
		if (debug == 1) {
			System.out.println("***" + s);
		}
	}

	public static void main(String args[]) throws UnsupportedEncodingException, NoSuchAlgorithmException {

		// check for debug option. If so, set debug flag to be on.
		if (args.length == 1 && args[0].equals("debug")) {
			System.out.println("Debug mode is on. Debug lines star with ***");
			User.debug = 1;
		}

		String user_name, password;
		Scanner input = new Scanner(System.in);
		System.out.println("LOGIN ");
		System.out.print("	username:");
		user_name = input.nextLine();
		System.out.print("	password:");
		password = input.nextLine();

		User user = new User();
		
		user.connectDB();

		int ret = user.login(user_name, password);

		if (ret != 0) {
			switch (ret) {
			case -1:
				System.out.println("You are logged in already from somewhere else. Please log out first");
				break;
			case -2:
				System.out.println("Wrong username or password");
				break;
			case -3:
				System.out.println("Encounted some unexpected SQL excetions. ");
				break;
			}
			user.disconnect();
			return;
		}

		// allow user to perform functions.
		user.stats();

		// logout the user
		user.logout(user_name);

		user.disconnect();

	}
}

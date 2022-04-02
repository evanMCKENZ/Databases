import java.sql.*; 
import java.util.Scanner;
import java.io.Console;

public class JDBCLab { 
 Connection conn = null; 
 Statement stmt = null; 
 ResultSet rs = null; 
 
 public int transfer_NoSQLInjection(String from_account_number, String to_account_number, 
double amount){ 
    PreparedStatement stmt = null; 
    ResultSet rs = null; 
    int rowcount; 
    
    try { 
    // start transaction 
    conn.setAutoCommit(false); 
    conn.setTransactionIsolation( 
        conn.TRANSACTION_SERIALIZABLE); 
    } catch (SQLException e) { 
        e.printStackTrace(); 
        return 0; 
    } 
 
    try { 
      String sqlstr; 
 
      sqlstr = "update lab2_account set balance = balance - ? where account_number = ?"; 
      stmt = conn.prepareStatement(sqlstr); 
 stmt.setDouble(1, amount); 
      stmt.setString(2, from_account_number); 
       
 rowcount = stmt.executeUpdate(); 
      System.out.println("deduct money from account "+ from_account_number + ": " + rowcount + " rows has been updated"); 
 
 
    sqlstr = "update lab2_account set balance = balance + ? where account_number = ?"; 
      stmt = conn.prepareStatement(sqlstr); 
  stmt.setDouble(1, amount); 
      stmt.setString(2, to_account_number); 
      rowcount = stmt.executeUpdate(); 
      System.out.println("save money to account "+ to_account_number + ": " + rowcount + " rows has been updated"); 
 
 
      conn.commit(); 
    } 
    catch (SQLException ex){ 
      // handle any errors 
      System.out.println("SQLException: " + ex.getMessage()); 
      System.out.println("SQLState: " + ex.getSQLState()); 
      System.out.println("VendorError: " + ex.getErrorCode()); 
    } 
 
    return 1; 
}



 public int transfer(String from_account_number, String to_account_number, double amount){ 
        Statement stmt = null; 
        ResultSet rs = null; 
        int rowcount; 
     
        try { 
            // start transaction 
            conn.setAutoCommit(false); 
            conn.setTransactionIsolation( 
                conn.TRANSACTION_SERIALIZABLE); 
        } catch (SQLException e) { 
            e.printStackTrace(); 
            return 0; 
        }    
             
        try { 
            String sqlstr; 
            stmt = conn.createStatement(); 
 
            sqlstr = "update lab2_account set balance = balance - " + amount + " where account_number = '" + from_account_number + "'"; 
 
            
  rowcount = stmt.executeUpdate(sqlstr); 
         System.out.println("deduct money from account "+ from_account_number + ": " + rowcount + " rows has been updated"); 
                            
            sqlstr = "update lab2_account set balance = balance + " + amount + " where account_number = '" + to_account_number + "'"; 
             
  rowcount = stmt.executeUpdate(sqlstr); 
        System.out.println("save money to account "+ to_account_number + ": " + rowcount + " rows has been updated"); 
 
 
            conn.commit(); 
        }    
        catch (SQLException ex){ 
            // handle any errors 
            System.out.println("SQLException: " + ex.getMessage()); 
            System.out.println("SQLState: " + ex.getSQLState()); 
            System.out.println("VendorError: " + ex.getErrorCode()); 
        }    
             
        return 1; 
    }

 public int connectDB(){   
  try { 
    conn = DriverManager.getConnection( 
                       "jdbc:mysql://classdb.it.mtu.edu/ecmckenz",  
                       "ecmckenz",  
                       "XXX"); 
   System.out.println("Connected to the database!"); 
  } catch (SQLException e) { 
   System.out.println(e.getMessage()); 
   e.printStackTrace(); 
   return 1; 
  }   
  return 0; 
 } 

public int newConnectDB(){ 
     try { 
      String username=null; 
         char[] password=null; 
         Console console = System.console(); 
         if (console == null) { 
            System.out.println("console is null. Run the program in terminal"); 
            return 1; 
         } 
         username = console.readLine("Please enter your name:"); 
         password = console.readPassword("Please enter your password:"); 
 
         conn = DriverManager.getConnection( "jdbc:mysql://classdb.it.mtu.edu/"+               
username, username, String.valueOf(password)); 
         System.out.println("Connected the the database!"); 
     } catch (SQLException e) { 
         System.out.println(e.getMessage()); 
         e.printStackTrace(); 
         return 1; 
     } 
     return 0; 
}

 public void disconnect(){ 
  try { 
   conn.close(); 
              System.out.println("Disconnected from the database!"); 
 
  } 
  catch (SQLException ex){ 
   System.out.println("SQLException: " +  
                                      ex.getMessage()); 
   System.out.println("SQLState: " + ex.getSQLState()); 
   System.out.println("VendorError: " +  
                                      ex.getErrorCode()); 
  } 
 } 
  
 public void displayAccount(){

  try {
   stmt = conn.createStatement();
   rs = stmt.executeQuery("SELECT account_number,balance FROM lab2_account");
   while (rs.next() ) {
        System.out.println(rs.getString(1)+ ","+rs.getString(2));
        }
  }
  catch (SQLException ex){
   System.out.println("SQLException: " +ex.getMessage());
   System.out.println("SQLState: " + ex.getSQLState());
   System.out.println("VendorError: " +ex.getErrorCode());
  }
 }

public static void main(String args[]){ 
 
        JDBCLab dblab = new JDBCLab(); 
        dblab.newConnectDB(); 
        dblab.displayAccount(); 
 
        String from_account, to_account; 
        Double balance; 
        Scanner input = new Scanner(System.in); 
        System.out.println("Enter the account to tranfer the money from:"); 
        from_account = input.nextLine(); 
        System.out.println("Enter the account to tranfer the money to:"); 
        to_account = input.nextLine(); 
        System.out.println("Enter the amount to withdraw:"); 
        balance = input.nextDouble(); 
        System.out.println("tranfering $" + balance + " from " + from_account + " to " + to_account); 
 
        dblab.transfer_NoSQLInjection(from_account, to_account, balance); 
 
        dblab.displayAccount(); 
        dblab.disconnect(); 
   }  
}

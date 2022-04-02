/*
 * CS 3425 Assignment 5
 * Authors: Evan McKenzie
*/

select "1.";
#1. List how many students for each department.
select d.dept_name, count(s.ID) as total_student from department d left join student s on d.dept_name=s.dept_name group by d.dept_name order by count(s.ID) desc, d.dept_name asc;

select "2.";
#2. List the five lowest populated departments.
select d.dept_name, count(s.ID) as total_student from department d left join student s on d.dept_name=s.dept_name group by d.dept_name order by count(s.ID) asc, d.dept_name asc limit 5;

select "3.";
#3. List all department statistics.
select distinct d.dept_name, count(distinct s.ID) as total_student, count(distinct i.ID) as total_instructor, count(distinct c.course_id) as total_course
from (department d left join student s on s.dept_name=d.dept_name left join instructor i on i.dept_name=d.dept_name left join
course c on c.dept_name=d.dept_name) group by d.dept_name order by total_student desc, total_instructor desc, total_course desc;

select "4.";
#4. List course information
select c.course_id, c.title, count(s.sec_id) as times from course c left outer join section s on c.course_id=s.course_id group by c.course_id order by count(s.sec_id) desc, c.title asc;

select "5.";
#5. Total number of sections by department in Spring 2008.
select d.dept_name, count(s.sec_id) as total_section from department d right outer join course c on d.dept_name=c.dept_name right outer join 
section s on c.course_id=s.course_id where s.semester="Spring" and s.year="2008" group by d.dept_name order by d.dept_name asc;

select "6.";
#6. Chronological transcript for student 1000.
select c.course_id, c.title, c.credits, t.grade, t.semester, t.year from course c left join takes t on c.course_id=t.course_id
where t.ID="1000" group by t.course_id, t.grade, t.semester, t.year order by t.year asc, t.semester desc;

select "7.";
#7. Total teaching credit hours for instructors in Spring 2008.
select i.dept_name, i.ID, i.name, coalesce(sum(c.credits),0) as total_credit_hours from (instructor i left outer join teaches e 
on e.ID=i.ID left outer join (select * from takes m where m.semester="Spring" 
and m.year="2008") as t on e.course_id=t.course_id left outer join course c on t.course_id=c.course_id)
group by i.dept_name, i.ID, i.name order by i.dept_name asc, total_credit_hours desc, i.name asc;

select "8.";
#8. Total student count for each instructor.
select i.dept_name, i.ID, i.name, count(distinct t.ID) as stu_number from instructor i left join teaches e on i.ID=e.ID
left join takes t on e.course_id=t.course_id group by i.dept_name, i.ID, i.name order by 
i.dept_name asc, stu_number desc, i.name asc;

select "9.";
#9. List the Math Department's top 20 student by GPA.
select t.ID, s.name, sum(c.credits) as total_credits, round(sum(g.point * c.credits)/sum(c.credits) , 2) as GPA from (takes t natural join course c join student s on t.ID=s.ID 
join gradepoint g on t.grade=g.grade) where s.dept_name="Math" group by t.ID, s.name order by GPA desc limit 20;

select "10.";
#10. List section information about 2008 Spring classes.
select distinct c.dept_name, s.course_id, s.sec_id, c.credits, c.title, s.capacity, count(distinct t.ID) as act, (s.capacity - count(distinct t.ID)) as rem,
case when i.name is null then "--TBD--" else i.name end as name, s.building, s.room_number from (course c right join section
s on s.course_id=c.course_id right join teaches e on e.course_id=s.course_id right join instructor i on e.ID=i.ID 
right join takes t on t.course_id=s.course_id) where s.semester="Spring" and s.year="2008" and t.semester=s.semester
group by c.dept_name, s.course_id, s.sec_id, c.credits, c.title, s.capacity, i.name, s.building, s.room_number order by c.dept_name asc,
s.course_id asc, s.sec_id asc;
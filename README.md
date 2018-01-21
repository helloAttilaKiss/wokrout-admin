# workout-admin
In this webpage you can
- Create
- Load
- Edit
- Delete
workout plans.

A plan has a name and consists of several (workout) days.
A day can have multiple exercises that you should perform that day.
A plan can be assigned to one or more user(s).
A user is an entity with personal data (firstname, lastname, email…)
A user can be added / edited / deleted.
Whenever an user is assigned to a workout plan, he(she) should receive an email confirmation.
Whenever a plan is modified/deleted, the user(s) connected should be notified of the change by mail.

Technologies
- Own MVC structure
- bootstrap 3
- Mobile responsive front-end
- jQuery
- Save on each mutation (ajax calls to RESTful json API, when users adds/removes an exercise to a
day, changes the name of a day etc)

In the Api folder, there are php files to handle the user cases as follows:

api.php : Case 1 Hr. Schneider
hirtz_api.php : Case 2 Hr. Hirtz
meier_api.php: Case 3 Fr. Meier
bauch_api.php : Case 4 Fr. Olewski
rigas_api.php: Case 5 Hr. Rigas
holderbaum_api.php: Case 6 Fr. Holderbaum

All *_api.php files are duplicates of the template api.php file. The only changes are the name of the tables for different patients as indicated by the table prefix. 

If a new case (Male Patient) is created, make a copy of rigas_api.php, and replace the tables with prefix rigas_ with the appropriate prefix (For instance, if the new patient is Hr.Smith, find and replace all instances of rigas_ with smith_ ).

If a new case (Male Patient) is created, make a copy holderbaum_api.php, and replace the tables with prefix rigas_ with the appropriate prefix (For instance, if the new patient is Fr.Smith, find and replace all instances of holderbaum_ with smith_ ).

Important: Make a copy of the following tables, and rename appropriately in the database as well:

(You can export these tables and rename them in the notepad and import them)

For male patients:

rigas_bluten_option
rigas_currentpage
rigas_doctor_option
rigas_isclicked
rigas_notepad
rigas_stuhl_options
rigas_submit_options
rigas_submit_options_original
rigas_urin_options
rigas_users
rigas_users_original

For female patients:

holderbaum_bluten_option
holderbaum_currentpage
holderbaum_doctor_option
holderbaum_isclicked
holderbaum_notepad
holderbaum_stuhl_options
holderbaum_submit_options
holderbaum_submit_options_original
holderbaum_urin_options
holderbaum_users
holderbaum_users_original

Use the SQL script in Empty_Diagnosis_Query.sql in the API folder to empty all the new tables. Recheck if all the fields are empty or null where appropriate.


The comments for the sql queries in all the _api.php files can be referenced from the comments done in api.php file.
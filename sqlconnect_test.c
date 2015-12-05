#include <mysql.h>
#include <stdio.h>

int main() {
   
   MYSQL *conn;
   MYSQL_RES *res;
   MYSQL_ROW row;
   char *server = "54.183.146.117";
   char *user = "DBPro";
   char *password = "*************"; /* set me first */
   char *database = "project";
   conn = mysql_init(NULL);
   /* Connect to database */
   if (!mysql_real_connect(conn, server,
         user, password, database, 0, NULL, 0)) {
      fprintf(stderr, "%s\n", mysql_error(conn));
      return 0;
   }
   /* send SQL query */
   if (mysql_query(conn, "SELECT * FROM Employees;")) {
      fprintf(stderr, "%s\n", mysql_error(conn));
      return 0;
   }
   res = mysql_use_result(conn);
   /* output table name */

   while ((row = mysql_fetch_row(res)) != NULL)
      printf("%s %s %s \n", row[0], row[1], row[2]);
   
  

   /* close connection */
   mysql_free_result(res);
   mysql_close(conn);
}
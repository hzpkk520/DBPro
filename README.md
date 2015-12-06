# DBPro


##Group Members 
  Ziping He, Chen (Stone) Zhu 

##KEY WORDS
  - Amazon AWS
  - Cloud Encryption
  - [Paillier](https://github.com/camillevuillaume/Paillier-GMP)(using GMP library)
  - [GMP](https://gmplib.org/)(for Paillier library)
  - Homomorphic Encryption
  - Mysql
  - Mysql UDF
  - C
  - PHP
  - Public Key
  - Private Key 

##OBJECTIVE
  This programm implements and evaluates a secure database service prototype that stores data encrypted in the cloud and allows clients to run aggregate SQL queries over it. To ensure data confidentiality, it uses homomorphic encryption schemes, which allow performing addition or multiplication operations over encrypted ciphertexts without the need for decryption while ensuring strong security. It is achieved by writing User-Defined-Function (UDF) for MySQL that implements the SUM operation over encrypted values.  The program connects to an instance on the Amazon AWS server, where a database is already constructed. The database has only one table called "Employees" and it has three fields: A numericid (primary key), another numeric field named age, and a NOT NULL TEXT field named salary. The salary field is encrypted using the homomorphic encrytion scheme mentioned above. A user could run queries using command line inputs and get the processed query results on his local machine. 
  Here are some sample queries user could enter on a local machine:
  - INSERT 12 45 95000
  - SELECT 25
  - SELECT *
  - SELECT SUM WHERE id>5
  - SELECT SUM WHERE id<4 OR id>= 8
  - SELECT SUM WHERE 2>id
  - SELECT SUM WHERE id>5 AND 2<id AND (age >= 15 OR id = 0)
  - SELECT SUM WHERE id>13 GROUP BY age
  - SELECT SUM GROUP BY age
  - SELECT SUM WHERE (NOT id>103) OR age<= 28 GROUP BY age HAVING COUNT(*)>2
  - SELECT SUM GROUP BY age HAVING age>35 OR age<30


##How to Run
  To compile and run the program, you need to first make sure that you have the public and private key files under the same directory as your program's directory. Then type "make" to compile. And type "php dbconnect.php" to run the program. You could then follow the prompt to test sql queries.

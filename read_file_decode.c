#include <stdio.h>
#include <stdlib.h>
#include <gmp.h>
#include <paillier.h>
#include <string.h>



void get_rand(void* buf, int len);

int main(void){
	paillier_pubkey_t* pub;//The public key
	paillier_prvkey_t* prv;//The private key 
	paillier_plaintext_t* sum; 
	paillier_plaintext_t* str = paillier_plaintext_from_ui(8);
	paillier_plaintext_t* str1 = paillier_plaintext_from_ui(2);

	void *buf2; 
	FILE *fp;
	int c;
	char *src; 
	char *src1;
	char *src2;
	src = (char *) malloc(260);
	src1 = (char *) malloc(260);
	

	paillier_ciphertext_t* result; 
	paillier_ciphertext_t* result1;
	paillier_ciphertext_t* res;


	// read public key, 
	fp = fopen( "pubkey.txt" , "r" );
	while(1){
		c = fgetc(fp);

      	if(feof(fp)){
        	break ;
    	}
   		strcat(src, &c); 
  	}
  	fclose(fp); 
  	pub = paillier_pubkey_from_hex(src); 
  	free(src); 


  	//get provate key .
  	fp = fopen( "prvkey.txt" , "r" );
	while(1){
		c = fgetc(fp);

      	if(feof(fp)){
        	break ;
    	}
   		strcat(src1, &c); 
  	}
  	fclose(fp); 
  	prv = paillier_prvkey_from_hex(src1, pub);
  	free(src1); 

  	//printf("this is pub key %s\n", paillier_pubkey_to_hex(pub));

  	//printf("this is prv key %s\n", paillier_prvkey_to_hex(prv));

  	//read encrypt 
  	src2 = (char *) malloc(500);
  	fp = fopen( "file.txt" , "r" );
  	while(1){
		c = fgetc(fp);

      	if(feof(fp)){
        	break ;
    	}
   		strcat(src2, &c); 
  	}
  	fclose(fp); 
  	result = paillier_ciphertext_from_bytes(src2, sizeof(src2)); 
  	free(src2); 


  	sum=paillier_dec(0, pub, prv, result);
	gmp_printf("The 1st is : %Zd\n", sum);











}
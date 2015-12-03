#include <stdio.h>
#include <stdlib.h>
#include <string.h> 
#include <ctype.h> 
#include <gmp.h>
#include <paillier.h>


int main(int argv, char* argc[]){
	char buff[40]; 
	char pubhex[40]; 
	char prvhex[40]; 
	paillier_pubkey_t *pub; 
	paillier_prvkey_t *prv;
	paillier_plaintext_t* sum;

	//1. read keys from local files--public key & private key 
	FILE *fp; 
	fp = fopen("pubkey.txt", "r");
	if(fp == NULL){
		perror("cant open pubkey file"); 
	} else {
		fgets(buff, 40, fp);
		strcpy(pubhex, buff);  
	}
	fclose(fp); 
	pub = paillier_pubkey_from_hex(pubhex);

	fp = fopen("prvkey.txt", "r");
	if(fp == NULL){
		perror("cant open prvkey file"); 
	} else {
		fgets(buff, 40, fp);
		strcpy(prvhex, buff);  
	}
	fclose(fp); 
	prv = paillier_prvkey_from_hex(prvhex, pub); 



	//2. for encode 
	if(strcmp(argc[2], "encode") == 0){
		
		//read plain ui number && encrypt it.
		paillier_plaintext_t *plaintext = paillier_plaintext_from_ui(atoi(argc[1]));
		//gmp_printf("encode : %Zd\n", pub);
		paillier_ciphertext_t *ciphertext = paillier_enc(0, pub, plaintext, paillier_get_rand_devurandom); 
		
		char *cipher;
		cipher = mpz_get_str(0, 16, ciphertext->c);
		printf("%s\n", cipher);
		return 0;

	} else { //decode mode 
		paillier_plaintext_t *plaintext; 
		paillier_ciphertext_t *ciphertext = (paillier_ciphertext_t *) malloc(sizeof(paillier_ciphertext_t));; 
		mpz_init_set_str(ciphertext->c, argc[1], 16); 
		plaintext = paillier_dec(0, pub, prv, ciphertext);
		unsigned long int actualnumber = mpz_get_ui(plaintext->m); 

		printf("%lu\n", actualnumber);
	}

}




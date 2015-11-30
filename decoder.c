#include <stdio.h>
#include <stdlib.h>
#include <gmp.h>
#include <paillier.h>



void get_rand(void* buf, int len);

int main(void){
	paillier_pubkey_t* pub;//The public key
	paillier_prvkey_t* prv;//The private key 
	paillier_plaintext_t* sum; 
	paillier_plaintext_t* str = paillier_plaintext_from_ui(8);
	paillier_plaintext_t* str1 = paillier_plaintext_from_ui(2);

	void *buf2; 
	FILE *fp;
	

	paillier_ciphertext_t* result; 
	paillier_ciphertext_t* result1;
	paillier_ciphertext_t* res;

	paillier_keygen(1024, &pub, &prv, paillier_get_rand_devurandom);
	
	
	fp = fopen( "pubkey.txt" , "w" );
	fwrite(paillier_pubkey_to_hex(pub), 1, 256, fp);
	//printf("this is pub key %s\n", paillier_pubkey_to_hex(pub));
	fclose(fp);

	fp = fopen( "prvkey.txt" , "w" );
	fwrite(paillier_prvkey_to_hex(prv), 1, 256, fp);
	//printf("this is prv key %s\n", paillier_prvkey_to_hex(prv));
	fclose(fp);

	result = paillier_enc(0, pub, str, paillier_get_rand_devurandom); 
	result1 = paillier_enc(0, pub, str1, paillier_get_rand_devurandom); 

	
	unsigned long int tmp = mpz_get_ui(result->c); 
	printf("mpz_get_ui %ld\n",mpz_get_ui(result->c)); 

	gmp_printf("encode : %Zd\n", result);

	//buf2=paillier_ciphertext_to_bytes(PAILLIER_BITS_TO_BYTES(pub->bits)*2,result);
	fp = fopen( "file.txt" , "w" );
	fprintf(fp, "%ld", tmp);
	//fwrite(&tmp, 1, sizeof(tmp), fp);
	fclose(fp);



	res=paillier_create_enc_zero();
	paillier_mul(pub, res, result, result1);

	sum=paillier_dec(0, pub, prv, result);
	gmp_printf("The 1st is : %Zd\n", sum);

	sum=paillier_dec(0, pub, prv, result1);
	gmp_printf("The 1st is : %Zd\n", sum);

	sum=paillier_dec(0, pub, prv, res);

	printf("mpz_get_ui %lu\n",mpz_get_ui(sum->m)); 
	gmp_printf("The sum is : %Zd\n", sum);  


	return 0; 
}

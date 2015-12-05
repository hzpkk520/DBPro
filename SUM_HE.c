#ifdef STANDARD
/* STANDARD is defined, don't use any mysql functions */
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#ifdef __WIN__
typedef unsigned __int64 ulonglong;     /* Microsofts 64 bit types */
typedef __int64 longlong;
#else
typedef unsigned long long ulonglong;
typedef long long longlong;
#endif /*__WIN__*/
#else
#include <my_global.h>
#include <my_sys.h>
#endif
#include <mysql.h>
#include <ctype.h>

//                                                                        
// User #includes go here                                                 
//
#include <gmp.h>
#include "libpaillier/paillier.h"

//                                                                        
// init, deinit and actual function prototypes here                       
//            
my_bool SUM_HE_init(UDF_INIT *initid, UDF_ARGS *args, char *message);
void SUM_HE_deinit(UDF_INIT *initid);
void SUM_HE_clear(UDF_INIT *initid, char *is_null, char *error);
void SUM_HE_add(UDF_INIT *initid, UDF_ARGS *args, char *is_null, char *error);
char* SUM_HE(UDF_INIT *initid, UDF_ARGS *args, char *result, unsigned long *length,char *is_null, char *error);

//                                                                        
// init function                                                          
//           
my_bool SUM_HE_init(UDF_INIT *initid, UDF_ARGS *args, char *message){
        // The most important thing to do here is setting up the memory
        // you need...
        // Lets say we need a lonlong type variable to keep a checksum
        // Although we do not need one in this case
        char* finalSum = (char*)malloc(150*sizeof(char)); // create the variable
        strcpy(finalSum,"db5ea54bbf75605db50abb091a8a18219e7935b720314ce5b92d9bb1550963e");
        
        // store it as a char pointer in the pointer variable
        // Make sure that you don`t run in typecasting troubles later!!
        initid->ptr = (char*)finalSum;
        
        // check the arguments format
        if (args->arg_count != 1){
            strcpy(message,"SUM_HE() requires one argument");
            return 1;
        }

        if (args->arg_type[0] != STRING_RESULT){
            strcpy(message,"SUM_HE() requires a string");
            return 1;
        }
        return 0;
}

void SUM_HE_clear(UDF_INIT *initid, char *is_null, char *error){
    /* The clear function resets the sum to 0 for each new group
    Of course you have to allocate a paillier_ciphertext_t variable in the init 
    function and assign it to the pointer as seen above */
    strcpy((char*)initid->ptr,"db5ea54bbf75605db50abb091a8a18219e7935b720314ce5b92d9bb1550963e");
  	// *((char*)initid->ptr) = "";
}

void SUM_HE_add(UDF_INIT *initid, UDF_ARGS *args, char *is_null, char *error){
        // For each row the current value is added to the sum

		//1. Initialize current sum and the read-in sum to be in paillier_ciphertext_t*
		char *curSUmString = (char *)initid->ptr;
		paillier_ciphertext_t *curSum;
		curSum = (paillier_ciphertext_t *) malloc(sizeof(paillier_ciphertext_t));
		mpz_init_set_str(curSum->c, curSUmString, 16);

		char *readInSumString = (char *)args->args[0];
		paillier_ciphertext_t *readInValue;
		readInValue = (paillier_ciphertext_t *) malloc(sizeof(paillier_ciphertext_t));
		mpz_init_set_str(readInValue->c, readInSumString, 16);


		//initialize public key
		char* pubKeyHex = "a9ae9dcdb3fb610f13073de30c8be44d";
		paillier_pubkey_t* pubKey = paillier_pubkey_from_hex(pubKeyHex);

		//add the new encrypted value to the current sum
		paillier_mul(pubKey, curSum, curSum, readInValue);

		strcpy((char*)initid->ptr, mpz_get_str(NULL, 16, curSum->c));

		free(curSum);
		free(readInValue);


}


//                                                                        
// deinit function                                                        
//   
void SUM_HE_deinit(UDF_INIT *initid)
{
	free((char*)initid->ptr);
}

                                                                  
//                                                                        
// Actual function                                                        
//                                                                        
char* SUM_HE(UDF_INIT *initid, UDF_ARGS *args, char *result, unsigned long *length,char *is_null, char *error){
	char *tmp = (char*)initid->ptr; 
	*length = strlen(tmp); 
	//result = tmp; 

	memcpy(result, tmp, *length);

   // And in the end the sum is returned
	return result;
}
                                                                     


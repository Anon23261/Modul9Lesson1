#ifndef DATABASE_H
#define DATABASE_H

#include <sqlite3.h>
#include "product.h"

#define DB_FILE "bakery.db"

extern sqlite3 *db;

int init_db(void);
void close_db(void);
int execute_query(const char *query);
int get_last_insert_id(void);

#endif // DATABASE_H

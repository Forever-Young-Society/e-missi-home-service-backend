# GUDELINES FOR DB CREATION

#### Please keep in mind before creating DB structure.
1. Column names MUST be in United States English
2. Column names MUST be in lower snake case
3. Column names MUST be singular
4. Column names SHOULD NOT match [reserved words](https://dev.mysql.com/doc/refman/5.7/en/keywords.html "reserved words")
5. Columns for possibly undefined values MUST be nullable
6. Columns for required values MUST NOT be nullable
7. Columns MUST use the correct type for the information they need to store
8. Columns MAY have a default value. If a nullable column has a default value, this default value SHOULD be *null*

#### The *id* column
- MUST be named *id*
- MUST be unsigned
- SHOULD be of type integer
- SHOULD be the first column in a table definition

###### Good column names:
    id
    vehicle_type_id

###### Bad column names:
    ID
    vehicleTypeId

#### Foreign keys
Columns that reference other tables MUST have a foreign key relation to that other table. The name of the column SHOULD be of the form `<referenced_table_name>`_id

#### Index
Columns that are used in search more freqeuently must be indexed


#### Formatting
- Keywords MUST be written in upper case
- MySQL function names are considered keywords
- Table names and column names MUST be written as they are defined, in lower case
- Indents MUST be two spaces
- Keywords MUST be followed by exactly one space
- Keywords that consist of multiple words MUST be written on one line
- Queries SHOULD be parametrized statements
- Parameters SHOULD be specified by name
- Operators MUST be surrounded by one space
- Table names, column names and database names SHOULD NOT be surrounded with backticks
- If a column name matches a keyword, the use of backticks is REQUIRED

##### Yii2 CRUD Guidelines for creating DB
We are following a design patterns that should be must follow by every individuals. After column `id` you must add a column `title` or `name`.

If in a table some columns have null value, you must keep it as default null value.
For detailed instructions - [Click Here](https://www.yiiframework.com/wiki/227/guidelines-for-good-schema-design#do-name-your-database-tables-in-the-singular-not-plural "Click Here")

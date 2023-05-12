# These are the Access Control Rules for common modules according to Roles 
===============================

## Logger 
-------


| Controller | Action   | Admin | Manager | User
| :---       | :---:    | :---: |  :---:  |

| Default    |  ADD     | true  | false   |false

| Log        |  INDEX   | true  | false   |false
|            |  CLEAR   | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  DELETE  | true  | false   |false


## Backup 
-------

| Controller | Action    | Admin | Manager | User
| :---       | :---:      | :---: |  :---:  |

| Default    |  INDEX     | true  | false   |false
|            |  RESTORE   | true  | false   |false
|            |  CREATE    | true  | false   |false
|            |  DELETE    | true  | false   |false
|            |  DOWNLOAD  | true  | false   |false


## Settings 
-------

| Controller | Action   | Admin | Manager | User
| :---       | :---:    | :---: |  :---:  |
| Default    |  INDEX   | true  | false   |false

| Variable   |  INDEX   | true  | false   |false
|            |  ADD     | true  | false   |false
|            |  UPDATE  | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLEAR   | true  | false   |false
|            |  CLONE   | true  | false   |false
|            |  DELETE   | true  | false   |false


## Seo 
-------

| Controller | Action  | Admin | Manager | User | Guest
| :---       | :---:   | :---: |  :---:  |

| Analytics  |  ADD     | true  | true    |false
|            |  UPDATE  | true  | true    |false
|            |  VIEW    | true  | true    |false
|            |  INDEX   | true  | true    |false
|            |  CLEAR   | true  | false   |false
|            |  DELETE  | true  | false   |false

| Default    |  INDEX   | true  | false   |false

| Log        |  INDEX   | true  | true    |false
|            |  VIEW    | true  | false   |false
|            |  CLEAR   | true  | false   |false
|            |  CLONE   | true  | false   |false
|            |  DELETE  | true  | false   |false

| Manager    |  INDEX   | true  | true    |false
|            |  VIEW    | true  | true    |false
|            |  UPDATE  | true  | true    |false
|            |  CLONE   | true  | true    |false
|            |  CLEAR   | true  | false   |false
|            |  DELETE  | true  | false   |false


| Redirect   |  INDEX   | true  | true    |false
|            |  ADD     | true  | true    |false | 
|            |  VIEW    | true  | true    |true  | true
|            |  UPDATE  | true  | true    |false
|            |  CLONE   | true  | true    |false
|            |  CLEAR   | true  | false   |false
|            |  DELETE  | true  | false   |false


## SMTP 
-------

| Controller | Action            | Admin | Manager | User | Guest
| :---       | :---:             | :---: |  :---:  |      |
| Default    |  INDEX            | true  | false   |false |false

| Account    |  ADD              | true  | false   |false |false
|            |  UPDATE           | true  | false   |false |false
|            |  VIEW             | true  | false   |false |false
|            |  CLONE            | true  | false   |false |false
|            |  AJAX             | true  | false   |false |false
|            |  MASS             | true  | false   |false |false
|            |  EXPORT           | true  | false   |false |false
|            |  TEST             | true  | false   |false |false
|            |  IMPORT           | true  | false   |false |false
| EmaiQueue  |  INDEX            | true  | false   |false |false
|            |  VIEW             | true  | false   |false |false
|            |  UNSCUBSCRIBE     | true  | true    |true  |true
|            |  SUBSCRIBE        | true  | true    |true  |true
|            |  SHOW             | true  | false   |false |false
|            |  IMAGE            | true  | true    |true  |true
| Unsubscribe| INDEX             | true  | true    |true  |false
|            | ADD               | true  | true    |false |false
|            | DELETE            | true  | false   |false |false
|            | CLEAR             | true  | false   |false |false
|            | UPDATE            | true  | true    |true  |false
|            | VIEW              | true  | true    |true  |true
|            | CLONE             | true  | false   |false |false
|            | SELECT-UNSUBSCRIBE| true  | true    |true  |false


## Storage 
-------

| Controller | Action   | Admin | Manager  | User
| :---       | :---:    | :---: |  :---:   |

| Default    |  INDEX   | true  | false   |false
| Provider   |  INDEX   | true  | false   |false
|            |  IMPORT  | true  | false   |false
|            |  ADD     | true  | false   |false
|            |  UPDATE  | true  | false   |false
|            |  CLONE   | true  | false   |false
|            |  EXPORT  | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLEAR   | true  | false   |false
| File       |  INDEX   | true  | false   |false
|            |  ADD     | true  | false   |false
|            |  UPDATE  | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLEAR   | true  | false   |false
| Type       |  INDEX   | true  | false   |false
|            |  ADD     | true  | false   |false
|            |  UPDATE  | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLEAR   | true  | false   |false
|            |  CLONE   | true  | false   |false



## Page 
-------

| Controller | Action  | Admin | Manager | User
| :---       | :---:    | :---: |  :---:  |

| Page       |  ADD     | true  | false   |false
|            |  UPDATE  | true  | false   |false
|            |  VIEW    | true  | false   |false



## Contact 
-------

| Controller | Action  | Admin | Manager | User
| :---       | :---:    | :---: |  :---:  |

| Address    |  ADD     | true  | false   |false
|            |  UPDATE  | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLONE   | true  | false   |false
| Phone      |  ADD     | true  | false   |false
|            |  UPDATE  | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLONE   | true  | false   |false
| Information|  INDEX   | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLEAR   | true  | false   |false
| Chatscript |  ADD     | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLONE   | true  | false   |false
|            |  UPDATE  | true  | false   |false

|Social Links|  ADD     | true  | false   |false
|            |  VIEW    | true  | false   |false
|            |  CLONE   | true  | false   |false
|            |  UPDATE  | true  | false   |false



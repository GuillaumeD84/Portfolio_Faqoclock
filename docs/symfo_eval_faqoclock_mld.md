# MLD : liste des tables et champs
Étapes :
1. entités => tables
2. 1:n deviennent des champs à ajouter à l'entité "côté 1" (foreign key)
3. n:m deviennent des tables "d'association"

---

## Liste des tables
### user
Table de l'entité USER
- **id** (*A_I PK*)
- **username** (*VARCHAR 100*)
- **password** (*VARCHAR 16*)
- **email** (*VARCHAR 255*)
- **is_active** (*TINYINT*)
- **role_id** (*FK ==> id de la table role*)

### role
Table de l'entité ROLE
- **id** (*A_I PK*)
- **name** (*VARCHAR 32*)

### question
Table de l'entité QUESTION
- **id** (*A_I PK*)
- **title** (*VARCHAR 255*)
- **body** (*TEXT*)
- **created_at** (*DATETIME*)
- **user_id** (*FK ==> auteur, id de la table user*)

### answer
Table de l'entité ANSWER
- **id** (*A_I PK*)
- **body** (*TEXT*)
- **is_blocked** (*TINYINT*)
- **is_validated** (*TINYINT*)
- **created_at** (*DATETIME*)
- **user_id** (*FK ==> auteur, id de la table user*)
- **question_id** (*FK ==> id de la table question*)

### tag
Table de l'entité TAG
- **id** (*A_I PK*)
- **title** (*VARCHAR 64*)

### question_tag
Table d'association liant les QUESTION aux TAG
- **question_id** (*PK FK ==> id de la table question*)
- **tag_id** (*PK FK ==> id de la table tag*)

### user_question_vote
Table d'association liant un USER aux QUESTION votées
- **user_id** (*PK FK ==> id de la table user*)
- **question_id** (*PK FK ==> id de la table question*)

### user_answer_vote
Table d'association liant un USER aux ANSWER votées
- **user_id** (*PK FK ==> id de la table user*)
- **answer_id** (*PK FK ==> id de la table answer*)

# Dico de données

Première liste des entités et de leurs champs

## QUESTION
- **author** (*username de l'auteur de la question*)
- **title** (*titre de la question*)
- **body** (*corps de texte la question*)
- **tags** (*liste des catégories associées*)
- **created_at** (*date de création de la question*)
- **vote** (*nombre de vote*)

## ANSWER
- **author** (*username de l'auteur de la réponse*)
- **body** (*corps de texte la réponse*)
- **created_at** (*date de création de la question*)
- **is_validated** (*l'auteur de la question peut valider la réponse*)
- **is_blocked** (*réponse bloquée par un modérateur*)
- **vote** (*nombre de vote*)

## USER
- **username** (*nom d'utilisateur de la personne inscrite*)
- **password** (*mot de passe de l'utilisateur*)
- **email** (*email de l'utilisateur*)
- **is_active**
- **roles** (*rôle de l'utilisateur*)

## TAG
- **title** (*titre de la catégorie*)

## ROLE
- **name** (*nom du rôle*)

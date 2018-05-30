<?php

namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Faker;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // On crée une instance de Faker en français
        $generator = Faker\Factory::create('fr_FR');

        // On ajoute un provider custom qui permet de fournir des catégories
        $generator->addProvider(new \AppBundle\DataFixtures\Faker\Provider\Tag($generator));

        // On passe le Manager de Doctrine à Faker !
        $populator = new Faker\ORM\Doctrine\Populator($generator, $manager);

        // Role
        $roles = array('admin', 'manager', 'user');
        $rolesEntities = array();

        for ($i=0; $i < count($roles); $i++) {
            $role = new Role();
            $role->setName('ROLE_'.strtoupper($roles[$i]));

            $rolesEntities[] = $role;
            $manager->persist($role);
        }

        // User
        $users = array('admin', 'manager', 'user', 'jc', 'blacksavate', 'carlito', 'guillaume', 'julien', 'maverick', 'romain');
        $usersEntities = array();

        for ($i=0; $i < count($users); $i++) {
            $user = new User();
            $user->setUsername($users[$i]);
            $user->setPassword($this->encoder->encodePassword($user, $users[$i]));
            $user->setEmail($users[$i].'.email@faqoclock.com');

            if ($i <= 2) {
                $user->setRole($rolesEntities[$i]);
            } else {
                $user->setRole($rolesEntities[2]);
                $usersEntities[] = $user;
            }

            $manager->persist($user);
        }

        // Tag
        $populator->addEntity('AppBundle\Entity\Tag', 10, [
            'title' => function() use ($generator) { return $generator->unique()->tag(); },
        ]);

        // Question
        $questionList = $populator->addEntity('AppBundle\Entity\Question', 30, [
            'title' => function() use ($generator) { return (rtrim($generator->unique()->sentence($nbWords = 10, $variableNbWords = true), '.') . ' ?'); },
            'body' => function() use ($generator) { return $generator->unique()->paragraph($nbSentences = 6, $variableNbSentences = true); },
            'createdAt' => function() use ($generator) { return $generator->unique()->dateTime($max = 'now', $timezone = null); },
            'author' => function() use ($generator, $usersEntities) { return $usersEntities[$generator->numberBetween($min = 0, $max = (count($usersEntities)-1))]; },
        ]);

        // Answer
        $populator->addEntity('AppBundle\Entity\Answer', 50, [
            'body' => function() use ($generator) { return $generator->unique()->paragraph($nbSentences = 1, $variableNbSentences = true); },
            'createdAt' => function() use ($generator) { return $generator->unique()->dateTime($max = 'now', $timezone = null); },
            'isBlocked' => false,
            'author' => function() use ($generator, $usersEntities) { return $usersEntities[$generator->numberBetween($min = 0, $max = (count($usersEntities)-1))]; },
        ]);

        $insertedEntities = $populator->execute();

        // On doit ajouter manuellement des données pour les ManyToMany
        $insertedTags = $insertedEntities['AppBundle\Entity\Tag'];
        $insertedQuestions = $insertedEntities['AppBundle\Entity\Question'];
        $insertedAnswers = $insertedEntities['AppBundle\Entity\Answer'];

        // Ajout de 1 à 3 tag(s) pour chaque question
        foreach($insertedQuestions as $question) {
            // On fait une copie des tags ajoutés
            $tagsTemp = $insertedTags;
            // On choisi aléatoirement le nombre de tag à ajouter
            $tagCount = mt_rand(1, 3);

            for($i = 1; $i <= $tagCount; $i++) {
                // On ajoute un tag random à la question
                $randIndex = array_rand($tagsTemp);
                $question->addTag($insertedTags[$randIndex]);

                // On le retire ensuite du tableau pour éviter les doublons
                unset($tagsTemp[$randIndex]);
            }
        }

        // On associe des votes aux questions
        foreach($insertedQuestions as $question) {
            // On fait une copie des utilisateurs
            $usersTemp = $usersEntities;
            // On décide si oui ou non la question à obtenu des votes
            $isVoted = mt_rand(0, 1);

            if ($isVoted) {
                $voteCount = mt_rand(1, count($usersEntities));

                for($i = 1; $i <= $voteCount; $i++) {
                    // On sélectionne aléatoirement un utilisateur
                    $randIndex = array_rand($usersTemp);
                    $usersEntities[$randIndex]->addVoteQuestion($question);

                    // On le retire ensuite du tableau pour éviter les doublons
                    unset($usersTemp[$randIndex]);
                }
            }
        }

        // On associe des votes aux réponses
        foreach($insertedAnswers as $answer) {
            // On fait une copie des utilisateurs
            $usersTemp = $usersEntities;
            // On décide si oui ou non la réponse à obtenue des votes
            $isVoted = mt_rand(0, 1);

            if ($isVoted) {
                $voteCount = mt_rand(1, count($usersEntities));

                for($i = 1; $i <= $voteCount; $i++) {
                    // On sélectionne aléatoirement un utilisateur
                    $randIndex = array_rand($usersTemp);
                    $usersEntities[$randIndex]->addVoteAnswer($answer);

                    // On le retire ensuite du tableau pour éviter les doublons
                    unset($usersTemp[$randIndex]);
                }
            }
        }

        $manager->flush();
    }
}

<?php
/**
 * Extended class to handle easy change of themes, creation of forms, database service etc.
 */
namespace CR\DI;

class CDIFactoryExtended extends \Anax\DI\CDIFactoryDefault
{
       /**
         * Construct.
         *
         */
       public function __construct()
       {
       	parent::__construct();

       	$this->setShared('theme', function () {
       		$themeEngine = new \CR\ThemeEngine\CThemeExtended();
       		$themeEngine->setDI($this);
       		$themeEngine->configure(ANAX_APP_PATH . 'config/theme.php');
       		return $themeEngine;
       	});

        // Forms service
        $this->set('form', '\Mos\HTMLForm\CForm');

        // Database service for Anax
        $this->setShared('db', function () {
          $db = new \Mos\Database\CDatabaseBasic();
          $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql_wgtotw.php');
          $db->connect();
          return $db;
        });

        // Flash messages
        $this->setShared('flashmessage', function() {
          $flashmessages = new \helikopterspark\FlashMsg\FlashMsg();
          $flashmessages->setDI($this);
          return $flashmessages;
        });

        // Questions
        $this->setShared('QuestionController', function() {
          $questions = new \CR\Question\QuestionController();
          $questions->setDI($this);
          return $questions;
        });

        // Answers
        $this->setShared('AnswerController', function() {
          $answers = new \CR\Answer\AnswerController();
          $answers->setDI($this);
          return $answers;
        });

        // Tags
        $this->setShared('TagController', function() {
          $tags = new \CR\Tag\TagController();
          $tags->setDI($this);
          return $tags;
        });

        // Comments
        $this->set('CommentsController', function() {
          $commentscontroller = new \CR\Comment\CommentsController();
          $commentscontroller->setDI($this);
          return $commentscontroller;
      });

        // Users
        $this->set('UsersController', function() {
          $userscontroller = new \CR\Users\UsersController();
          $userscontroller->setDI($this);
          return $userscontroller;
      });

      // User login
      $this->set('UserloginController', function() {
          $userlogincontroller = new \CR\Users\UserloginController();
          $userlogincontroller->setDI($this);
          return $userlogincontroller;
      });

      // Vote service
      $this->setShared('vote', function() {
          $votes = new \CR\Vote\Vote();
          $votes->setDI($this);
          return $votes;
      });

      }

    }

<?php
    class FlashSnippet extends Snippet
    {
        private $content;
        
        public function __construct($message)
        {
            $this->message = $message;
        }
        
        public function genContent()
        {
            ?>
            <div id="flash">
                <p><?php echo htmlentities($this->message); ?></p>
            </div>
            <?php
        }
    }
?>


<?php
    class FlashSnippet extends Snippet
    {
        preivate $content;
        
        public function __construct($message)
        {
            $this->message = $message;
        }
        
        public function genContent()
        {
            ?>
            <div id="flash">
                <?php echo htmlentities($message); ?>
            </div>
            <?php
        }
    }
?>


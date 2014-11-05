<?php
    class ScriptsSnippet extends Snippet
    {
        private $scripts;
        
        public function __construct($scripts)
        {
            $this->scripts = $scripts;
        }
        
        public function genContent()
        {
            foreach($this->scripts as $script)
            {
                ?>
                    <script type="text/javascript" src="<?php echo $script; ?>"></script>
                <?php
            }
        }
    }
?>

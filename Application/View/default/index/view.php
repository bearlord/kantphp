<html>
    <title>view demo</title>
    <body>
        <?php echo $this->initro_title; ?>
        <br />
        name :<?php echo $this->intro_desc['name']; ?>
        <br />
        QQ: <?php echo $this->intro_desc['qq']; ?>
        <br />
        Notes: <br /><?php foreach ($this->intro_notes as $key => $val): ?>
        <?php echo $key; ?>: <?php echo $val; ?>
        <br />
        <?php endforeach; ?>
    </body>
</html>


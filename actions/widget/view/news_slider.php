<div id="cmo" class="cp_bg">
    <table>
        <tr>
            <td id="cmo1">
                <table>
                    <tr>
                    <?php foreach($list as $item){ ?>
                        <td align="center">
                            <a href="<?php echo $this->url('news.show', "id={$item['id']}") ?>">
                                <img src="<?php echo $item['img'] ?>"/>
                                <br/>
                                <?php echo $item['titl'] ?>
                            </a>
                        </td>
                    <?php } ?>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript" src="style/scroll.js"></script>
<script type="text/javascript">
    m2 = new Move('cmo', 'left');
    m2.start('cmo1');
</script>
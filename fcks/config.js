﻿/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.language = 'zh-cn';//设置中文语言
    config.font_names = '宋体;楷体_GB2312;新宋体;黑体;隶书;幼圆;微软雅黑;Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana';
    
    config.toolbar_Full =  
    [  
        ['Source','-','Preview','-','Templates'],  
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],  
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],  ['TextColor','BGColor'],  
        
        ['Maximize', 'ShowBlocks','-','About'] ,  
        ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],  

        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],  
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],  
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],  
        ['Link','Unlink','Anchor'],  
          ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],  

        ['Styles','Format','Font','FontSize'] 
    ];  
    config.toolbar_Basic =  
    [  
        ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']  
    ];  
   
    config.width =700;  
    config.height = 300;  
};

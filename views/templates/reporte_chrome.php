<page backtop="30mm" backbottom="20mm" backleft="10mm" backright="10mm">
    <page_header>
        <table style="width: 100%;">
            <tr>
                <td style="width: 100%;text-align:left;">
                    {{ asset:image file="pdf/cintillo_header.png" style="width:100%;" }}  
                </td>
            </tr>     
        </table>
    </page_header>
    <h4 align="center">{{title}}</h4>
    <p align="right">{{total}}</p>
    <br />
    
    <table >
        <thead>
           <tr> {{table_header}}</tr>
        </thead>
                            {{table}}
    </table>
    <page_footer>
        <table>
        <tr>
            <td width="200"></td> 
           
            <td width="300" style="border-top: #7A7A7A 1px solid;">
                 <p style="text-align: center; font-size: 16px;"><strong>Atentamente</strong></p>
            </td>
            <td width="200"></td>
             
        </tr>
    </table>
    </page_footer>
 

 </page>
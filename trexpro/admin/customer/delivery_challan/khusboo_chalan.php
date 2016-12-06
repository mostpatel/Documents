<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$delivery_challan_id=$_GET['id'];
$delivery_challan=getDeliveryChallanById($delivery_challan_id);
if(is_array($delivery_challan) && $delivery_challan!="error")
{
	$customer=getCustomerDetailsByCustomerId($delivery_challan['customer_id']);
	$vehicle=getVehicleById($delivery_challan['vehicle_id']);
	$vehicle_model = getVehicleModelById($vehicle['model_id']);
	$insurance = getInsuranceForDeliveryChallanID($delivery_challan_id);

}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}

?>
<link rel="stylesheet" href="../../../css/challan.css" />
<div class="mainInvoiceContainer">

    <div class="borderAround">
      
        <div class="sectionOne">
        
          <div class="leftSectionOne">
            <img src="<?php echo WEB_ROOT."images/logo.png" ?>" style="min-width:200px;min-height:95px" />            
          </div>   <!-- End of LeftSectionOne -->
          
          <div class="rightSectionOne">
          
            <div class="companyName">KHUSHBU AUTO PVT. LTD.</div>
            
            <div class="address">
            Ahmedabad
            
            </div>   <!-- End of address -->
            
            
            
          </div>   <!-- End of rightSectionOne -->
          
          <div class="clearFix"></div>
          
        </div> <!-- End of SectionOne -->
        
        <div class="sectionTwo">
        
        <div class="chalanNo">
        ડીલીવરી ચલન નં. : <?php   echo $delivery_challan['challan_no']; ?>
        </div>   <!-- End of chalanNo -->
        
        <div class="date">
        તારીખ : <?php   echo date('d/m/Y',strtotime($delivery_challan['delivery_date'])); ?>
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionTwo -->
        
        <div class="sectionThree">
         
         <div class="leftDivInSectionThree">
           <table>
             
              <tr>
              
                <td>
                નામ : <?php echo $customer['customer_name']; ?>

                </td>
                
                <td>
                </td>
                
              </tr>
              
              <tr>
                 <td>
                 સરનામું : <?php echo $customer['customer_address']; ?>

                </td>
                
                <td>
                </td>
                
            </tr>
              
               
              
              <tr>
                <td>
                ફોન : <?php echo $customer['contact_no'][0][0]; ?>
                </td>
                
                <td>
                </td>
              </tr>
              
           </table>
         </div>
         
         <div class="rightDivInSectionThree">
         <b>	Vaibhav Auto Parts & Service Station</b>
         <br />
          Shop No.: 1/2, Raghuvir chambers, 
    Opp. S.T. Bus Stop, Naroda Gam, 
    Ahmedabad-30.<br> Ph : 22810533, Fax : 22815708, Mob : 9825043973
         </div>
           
         <div class="clearFix"></div> 
        </div> <!-- End of sectionThree -->
        
        <div class="sectionFour font">
         
           
           <div class="sectionFourLeft">
             
             <div class="sectionFourLeftTitle">
             મોડેલ : <?php echo getModelNameById($vehicle['model_id']); ?>	
             </div>  <!-- End of sectionFourLeftTitle -->
             
             <table>
             
              <tr>
              
                <td>
                ચેસીસ નંબર : <?php echo $vehicle["vehicle_chasis_no"]; ?>
                </td>
                
                <td>
                </td>
                
              </tr>
              
              <tr>
                 <td>
                 એન્જીન નંબર : <?php echo $vehicle["vehicle_engine_no"]; ?>
                 </td>
                
                <td>
                </td>
                
            </tr>
              
               
              
              <tr>
                <td>
                કલર : <?php echo getVehicleColorNameById($vehicle['vehicle_color_id']); ?>
                </td>
                
                <td>
                </td>
              </tr>
              
              <tr>
                <td>
                ચાવી નંબર : <?php echo $vehicle["key_no"]; ?>


                </td>
                
                <td>
                </td>
              </tr>
              
              <tr>
                <td>
               બેટરી નંબર : <?php echo $vehicle["battery_no"]; ?>
       </td>
                
                <td>
                </td>
              </tr>
              
              <tr>
                <td>
                
સર્વિસ બુક નંબર : <?php echo $vehicle["service_book"]; ?>

         
                </td>
                
                <td>
                </td>
              </tr>
              
              <tr>
                <td>
                
ટેન્ક નંબર : <?php echo $vehicle["cng_cylinder_no"]; ?>


                </td>
                
                <td>
                </td>
              </tr>
              
              <tr>
                <td>
                કીટ નંબર : <?php echo $vehicle["cng_kit_no"]; ?>
                </td>
                
                <td>
                </td>
              </tr>
              
              
              
           </table>
             
           </div> <!-- End of sectionFourLeft -->
           
           <div class="sectionFourMiddle">
           
           <div class="sectionFourLeftTitle">
            વાહન સાથે મળેલી વસ્તુઓની વિગત : 

             </div>  <!-- End of sectionFourLeftTitle -->
             
             <table>
             
              <tr>
              
                <td>
                સ્પેર વ્હીલ :  <?php if($delivery_challan['spare_wheel_inc']==1) echo "Yes"; else echo "No"; ?>

                </td>
                
                <td>
                </td>
                
              </tr>
              
              <tr>
                 <td>
                જેક + ટોમી : <?php if($delivery_challan['service_book_inc']==1) echo "Yes"; else echo "No"; ?>

                 </td>
                
                <td>
                </td>
                
            </tr>
              
               
              
              <tr>
                <td>
                વ્હીલ પાનું + ટોમી : <?php if($delivery_challan['water_bottle_inc']==1) echo "Yes"; else echo "No"; ?>
                </td>
                
                <td>
                </td>
              </tr>
              
              <tr>
                <td>
                ટુલ કીટ : <?php if($delivery_challan['toolkit_inc']==1) echo "Yes"; else echo "No"; ?>




                </td>
                
                <td>
                </td>
              </tr>
              
              <tr>
                <td>
               સર્વિસ નંબર : <?php echo $vehicle["service_no"]; ?>
       </td>
                
                <td>
                </td>
              </tr>
              
              <tr>
                <td>
                
              બેટરી સર્વિસ બુક કાર્ડ નં. : <?php echo $vehicle["battery_service_book_no"]; ?>
         
                </td>
                
                <td>
                </td>
              </tr>
             
              
              
              
           </table>
           </div> <!-- End of sectionFourMiddle -->
           
           <div class="sectionFourRight">
           
            <div class="sectionFourLeftTitle">
            એન્જીન ગીયર અને ડીફરેશનમાં નીચે મુજબનું ઓઈલ વાપરવું 

             </div>  <!-- End of sectionFourLeftTitle -->
             
             <table border="1">
               <tr>
               
                <td>
                </td>
                
                <td>
                ડીઝલ
                </td>
               </tr>
               
               <tr>
               
                <td>
                એન્જીન
                </td>
                
                <td>
                15w40 / Ch4 ગ્રેડ 

                </td>
               </tr>
               
               <tr>
               
                <td>
                ગીયર
                </td>
                
                <td>
                25w/50 or w40
                </td>
               </tr>
               
               <tr>
               
                <td>
                ડીફરન્શીયલ
                </td>
                
                <td>
                140
                </td>
               </tr>
             
             </table>
             
           </div> <!-- End of sectionFourRight -->
           
           <div class="clearFix"></div>
           
        </div> <!-- End of sectionFour -->
        
        
        <div class="sectionFive">
        
         <div class="leftSectionFive">
         
         <ul>
         
         <li>
         Gem ગાડીમાં શરૂઆતમાં ૮ મહિનામાં ૬ ફ્રી સર્વિસ અને બાકીના ૧૬ મહિનામાં ૧૦ પેઈડ સર્વિસ છે. જેમાં લેબર ચાર્જ થશે. 
         </li>
         
         <li>
         Gem ગાડીમાં ૨૪ મહિના સુધી વોરંટી ચાલુ રાખવા માટે ૬ ફ્રી સર્વિસ તેમજ ૧૦ પેઈડ સર્વિસ રેગ્યુલર કરાવવી જરૂરી છે.
         </li>
         
         <li>
         બીજા વર્ષનાં ગાડીનો વીમો બ્રેક થયા વગર રીન્યુઅલ થવો જરૂરી છે. જો તેમ ન હોય તો વોરંટી મળશે નહિ. 
         </li>
         
         <li>
         ઘસારાવાળી સ્પેર્સમાં વોરંટી મળશે નહિ. 

         </li>
         
         </ul>
         </div>    <!-- End of leftSectionFive -->
         
         <div class="rightSectionFive">
         
         <table border="1">
         
           <tr>
           
             <td>
             ૧ લી સર્વિસ : 
             </td>
             
             <td>
             ૧૦૦૦ કી.મી. અથવા ૩૦ દિવસ, બે માંથી જે પેહલું આવે 
             </td>
             
           </tr>
           
           <tr>
           
             <td>
             ૨ જી સર્વિસ : 

             </td>
             
             <td>
             ૬૦૦૦ કી.મી. અથવા ૬૦ દિવસ, બે માંથી જે પેહલું આવે 
             </td>
             
           </tr>
           
           <tr>
           
             <td>
             ૩ જી સર્વિસ : 
             </td>
             
             <td>
             ૧૧૦૦૦ કી.મી. અથવા ૯૦ દિવસ, બે માંથી જે પેહલું આવે 
             </td>
             
           </tr>
           
           <tr>
           
             <td>
             ૪ થી સર્વિસ : 

             </td>
             
             <td>
             ૧૬૦૦૦ કી.મી. અથવા ૧૨૦ દિવસ, બે માંથી જે પેહલું આવે 
             </td>
             
           </tr>
           
           <tr>
           
             <td>
             ૫ મી  સર્વિસ : 
             </td>
             
             <td>
             ૨૧૦૦૦ કી.મી. અથવા ૧૫૦ દિવસ, બે માંથી જે પેહલું આવે 
             </td>
             
           </tr>
           
           <tr>
           
             <td>
             ૬ ઠ્ઠી સર્વિસ : 

             </td>
             
             <td>
             ૨૬૦૦૦ કી.મી. અથવા ૧૮૦ દિવસ, બે માંથી જે પેહલું આવે 
             </td>
             
           </tr>
           
         </table>
         
         </div>     <!-- End of rightSectionFive -->
         <div class="clearFix"></div>
        
        </div>   <!-- End of sectionFive -->
        
        
        <div class="sectionSix">
        
         <div class="leftSectionSix">
         
         <div class="bigHeadinginLastDiv">
         વોરંટી દરમ્યાન અતુલ શક્તિ માં વપરાતી ચાર્જેબલ વસ્તુઓ 
         </div>   <!-- End of bigHeadinginLastDiv -->
         
         <div class="chargableItems">
        
             બધા જ પ્રકાર ના ઓઈલ,
             બધી જ ઇલેક્ટ્રિક વસ્તુઓ, 
            
           
            બધા જ પ્રકાર ના કેબલ (વાયર),
            બધા જ પ્રકાર ના ગાસ્કેટ, 
            બધા જ પ્રકાર ના રબ્બર પાર્ટ્સ, 
            બ્રેક શું (બ્રેક ના ડટ્ટા),
            ક્લચ પ્લેટ (ફેસિંગ),
            બધા જ નટ-બોલ્ટ, વાઇસર તથા સ્પીંગ, 
            ડીઝલ પાઈપ તથા તેના વાઇસર,
            માઉનટીંગ ડટ્ટા,
            બધી જ જાત ની સીલ - ઘસારાવાળી પાર્ટ્સ જે કે પીન બુસ , કોન સેટ, નીડલકેજ, બેરીંગ વગેરે. 
         </div>    <!-- End of chargableItems -->
         
         <div class="note">
         નોંધ : ઉપર નોંધેલ સિવાય પણ જો કોઈ પાર્ટ્સ વોરંટીમા આવતી નહિ હોય તો તેના ચાર્જ (પૈસા) અલગથી ચૂકવવાનો રહેશે. 

         </div>    <!-- End of note -->
         
         <div class="leftSideBullets">
         
         <ul>
         
           <li>
           વોરંટી પૂરી થયા બાદ બધા જ પાર્ટ્સ નો ચાર્જ (પૈસા) તથા મજુરી આપવાની રહેશે.

           </li>
           
           <li>
           રીક્ષા સર્વિસ માટે સવારના વહેલા લાવવા વિનંતી.

           </li>
           
           <li>
           પંપ નોજલની વોરંટી ડાયરેક્ટ માઈક્રો કંપનીના નિયમ મુજબ વોરંટી લાગુ પડશે. 

           </li>
           
           <li>
           બેટરી જે કંપની ની હશે તેમાં ગ્રાહક શ્રી એ બેટરી કાર્ડ લઇ બેટરી ની ડીલર પાસે જવાનું રહેશે અને કંપની ના નિયમ મુજબ મેંન્યુફેકચરીંગ ફોલ્ટ હશે તો જ વોરંટી લાગુ પડશે. 

           </li>
           
           <li>
           ટાયર જે કંપનીના હશે તે કંપનીની વોરંટી અપાશે. 

           </li>
           
           <li>
           ઈરેગ્યુંલર સર્વિસ પર વોરંટી મળશે નહિ. 

           </li>
           
           <li>
           સર્વિસ મેનુઅલમા દર્શાવવા પ્રમાણે ગાડી મેઈનટેઈન કરવી જરૂરી છે.

           </li>
           
          
           
         </ul>
         
         </div>  <!-- End of leftSideBullets -->
         
         </div>    <!-- End of leftSectionSix -->
         
         <div class="rightSectionSix">
         
         <div class="rightSideBullets">
         
         <ul>
         
          <li>
           સર્વિસ ની જરૂરીયાત ઉભી થાય ત્યારે સર્વિસ બુક ઓથો. સર્વિસ પોઈન્ટ/સબડીલર/ડીલર ને બતાવવી જરૂરી છે. અને સર્વિસ રેકોર્ડ કમ્પ્લીટ હશે તો વોરંટી મળવાને પાત્ર થશે. 

           </li>
           
           <li>
           દરેક સર્વિસ મા એન્જીન ઓઈલ, ઓઈલ ફિલ્ટર, ડીઝલ ફિલ્ટર બદલવા જરૂરી છે. 
           </li>
         
           <li>
          વાહન મા અકસ્માત થયેલ પરિસ્થિતિ મા કોઈપણ પ્રકારની વોરંટી લાગુ પડતી નથી અને તે સમયે બદલવા યોગ્ય પાર્ટ્સ ન બદલતા ત્યારબાદ પણ તેની વોરંટી મળશે નહિ.


           </li>
           
           <li>
          સર્વિસ કંપની દ્વારા નિયુક્ત કરેલ સર્વિસ પોઈન્ટ ઉપર જ સર્વિસ કરાવવી તથા જે તે સર્વિસ પોઈન્ટની વિગતો સર્વિસબુકમા સહી-સિક્કા સાથે લખાવવાના રહેશે.


           </li>
           
           <li>
          યાદી વાહન ના કિલોમીટર છ માસ સુધી ૨૬,૦૦૦ કી.મી. પુરા થઇ જાય તો વોરંટી પાત્રતા ચાલુ રાખવા માટે ૫૦૦૦ કી.મી. અથવા દર માસ પ્રમાણે વાહન ની સર્વિસ ચાલુ રાખવી પડશે. છ માસ સુધી ઓથોરાઇઝ પોઈન્ટ પર સર્વિસ કરાવવી. આ નિયમિતતા યાદી નહિ જળવાય તો વોરંટી પાત્રતા રેહશે નહિ. 
 

           </li>
           
           <li>
           વોરંટી ના સમયગાળા દરમ્યાન કંપની ના ડીલર દ્વારા પ્રમાણભૂત થયેલ સ્પેરપાર્ટ્સ તથા લુબ્રીકન્ટ અન્ય સામાન જ વાપરવાનો રહેશે તથા તેમના દ્વારા સૂચવવામા આવેલા કામ યદી નહિ કરાવવાથી પણ વોરંટી તે વસ્તુ ની કેન્સલ થઇ શકે છે.  

           </li>
           
           <li>
           કંપની દ્વારા આપવામાં આવેલા વાહન ના (નીતિ-નિયમ) સ્પેશીફીકેશન યદી ચેડા કરેલ માલુમ પડશે તો ત્યારથી તેમની વોરંટી કેન્સલ થવા પામે છે. 

           </li>
           
           <li>
          વોરંટી સમયબાદ કરવા પડતા તમામ કામનો સ્પેરપાર્ટ ચાર્જ તથા મજુરી ચાર્જ ગ્રાહકે આપવાની રહેશે. 

           </li>
           
           <li>
           વર્કશોપ ની અંદર તથા તમામ કામ ની જવાબદારી ગ્રાહક ના શિરે રહેશે.

           </li>
           
           
           
         </ul>
         
         </div>  <!-- End of rightSideBullets -->
         </div>   <!-- End of rightSectionSix -->
         
         <div class="clearFix"></div>
        
        </div>  <!-- End of sectionSix -->
        
        <div class="sectionSeven">
        
             <div class="declaration">
             ઉપરોક્ત વિગતો / શરતો મેં વાંચેલ છે અને કંપની ની શરતો મને મંજુર છે. 
             </div>    <!-- End of declaration -->
             
             <div class="signDiv">
             
             <div class="ourSign">
             ડીલીવરી આપનાર ની સહી : ____________________________

             </div>     <!-- End of ourSign -->
             
             <div class="theirSign">
             ગ્રાહક ની સહી : _______________________________________

             </div>    <!-- End of theirSign -->
             
             </div>   <!-- End of signDiv -->
         
         
        </div>  <!-- End of sectionSeven -->
        
      
    </div>   <!-- End of borderAround -->
    
</div>  <!-- End of mainInvoiceContainer -->
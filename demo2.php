<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
 <script>
 
  // When the browser is ready...
  $(function() {
 
    $("#grantForm").validate({
 
        // Specify the validation rules
        rules: {
                   grantAuthority:"required",
                   grantVillage: "required",
                   agency: "required",
	            certificate:"required",
	            amount: "required",
                   startDate: "required",
	            endDate: "required",
	            grantArea: "required",
            }
 
 });
 $('#grantForm').submit(function(e){     
	     e.preventDefault();
            var $form = $(this);
            if(! $form.valid()) return false;
	     var data = $( "#grantForm" ).serialize();
            $.ajax({
			type:"POST",
			url:"",
			data:data,
			success:function(data)
			{
			       console.log(data);
 
			}
		});
        });
 });
</script>
 
<form role="form" method="POST" action="" id="grantForm">
    <table border="0" cellpadding="0" cellspacing="0" id="id-form">
        <tr>
            <td>
            </td>
 
            <td>
                <input type="hidden" id="grantMoneyId" name="grantMoneyId" class="inp-form" value="" />
            </td>
        </tr>
        <th valign="top">Village</th>
        <td>
            <input type="text" id="grantVillage" name="grantVillage" class="inp-form" /><span id="user_nameErr" style="color:red"></span></td>
        <td>
            <tr>
                <th valign="top">Estimating Agency</th>
                <td>
                    <input type="text" id="agency" name="agency" class="inp-form" /><span id="user_nameErr" style="color:red"></span></td>
                <td>
                </td>
            </tr>
            <tr>
                <tr>
                    <th valign="top"> Utilization Certificate</th>
                    <td>
                        <select class="styledselect_form_1" required id="certificate" name="certificate">
                            <option value="">Select</option>
                            <option value="Verified">Verified</option>
                            <option value="Panding">Panding</option>
 
 
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <th valign="top">Estimate Amount</th>
                    <td>
                        <input type="text" id="amount" name="amount" class="inp-form" /><span id="user_nameErr" style="color:red"></span></td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <th valign="top">Start Date</th>
                    <td>
                        <input type="text" name="startDate" id="from" class="date-pick inp-form"> <span id="startDateErr" style="color:red"></span></td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <th valign="top">End Date</th>
                    <td>
                        <input type="text" name="endDate" id="to" class="date-pick inp-form"> <span id="startDateErr" style="color:red"></span></td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <th valign="top">Area</th>
                    <td>
                        <input type="text" id="grantArea" name="grantArea" class="inp-form" /><span id="user_nameErr" style="color:red"></span></td>
                    <td>
                        <td></td>
                </tr>
 
 
 
                </td>
                <td></td>
            </tr>
 
            <tr>
                <th>&nbsp;</th>
                <td valign="top">
                    <input type="submit" value="Submit" id="addGrand" class="form-submit" />
 
                </td>
 
                <td></td>
            </tr>
            <tr>
                <td></td>
 
            </tr>
    </table>
 
</form>
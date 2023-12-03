<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>COMESA E::Procurement</title>

        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css" />
        <!-- jQuery UI theme -->
            <link rel="stylesheet" href="/assets/lib/jquery-ui/css/Aristo/Aristo.css" />
        <!-- breadcrumbs -->
            <link rel="stylesheet" href="/assets/lib/jBreadcrumbs/css/BreadCrumb.css" />
        <!-- tooltips-->
            <link rel="stylesheet" href="/assets/lib/qtip2/jquery.qtip.min.css" />
		<!-- colorbox -->
            <link rel="stylesheet" href="/assets/lib/colorbox/colorbox.css" />
        <!-- code prettify -->
            <link rel="stylesheet" href="/assets/lib/google-code-prettify/prettify.css" />
        <!-- sticky notifications -->
            <link rel="stylesheet" href="/assets/lib/sticky/sticky.css" />
        <!-- aditional icons -->
            <link rel="stylesheet" href="/assets/img/splashy/splashy.css" />
		<!-- flags -->
            <link rel="stylesheet" href="/assets/img/flags/flags.css" />
        <!-- datatables -->
            <link rel="stylesheet" href="/assets/lib/datatables/extras/TableTools/media/css/TableTools.css">

        <!-- wizard -->
            <link rel="stylesheet" href="/assets/lib/stepy/css/jquery.stepy.css" />

        <!-- main styles -->
            <link rel="stylesheet" href="/assets/css/style.css" />
		<!-- theme color-->
            <link rel="stylesheet" href="/assets/css/blue.css" id="link_theme" />

            <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>

        <!-- favicon -->
            <link rel="shortcut icon" href="/assets/favicon.ico" />

			
			<script src="/assets/js/jquery.min.js"></script>
			<script src="/assets/js/swal.min.js"></script>

			<meta name="csrf-token" content="{{ csrf_token() }}"/>

   </head>
    <body class="full_width">
        <div id="maincontainer" class="clearfix">

            <header>

				@include('includes.main-menu')

				<div class="modal fade" id="myMail">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h3 class="modal-title">New Messages</h3>
							</div>
							<div class="modal-body">
								<table class="table table-condensed table-striped" data-provides="rowlink">
									<thead>
										<tr>
											<th>Sender</th>
											<th>Subject</th>
											<th>Date</th>
											<th>Size</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Declan Pamphlett</td>
											<td><a href="/assets/javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
											<td>23/05/2015</td>
											<td>25KB</td>
										</tr>
										<tr>
											<td>Erin Church</td>
											<td><a href="/assets/javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
											<td>24/05/2015</td>
											<td>15KB</td>
										</tr>
										<tr>
											<td>Koby Auld</td>
											<td><a href="/assets/javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
											<td>25/05/2015</td>
											<td>28KB</td>
										</tr>
										<tr>
											<td>Anthony Pound</td>
											<td><a href="/assets/javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
											<td>25/05/2015</td>
											<td>33KB</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default">Go to mailbox</button>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="myTasks">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h3 class="modal-title">New Tasks</h3>
							</div>
							<div class="modal-body">
								<table class="table table-condensed table-striped" data-provides="rowlink">
									<thead>
										<tr>
											<th>id</th>
											<th>Summary</th>
											<th>Updated</th>
											<th>Priority</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>P-23</td>
											<td><a href="/assets/javascript:void(0)">Admin should not break if URL…</a></td>
											<td>23/05/2015</td>
											<td><span class="label label-danger">High</span></td>
											<td>Open</td>
										</tr>
										<tr>
											<td>P-18</td>
											<td><a href="/assets/javascript:void(0)">Displaying submenus in custom…</a></td>
											<td>22/05/2015</td>
											<td><span class="label label-warning">Medium</span></td>
											<td>Reopen</td>
										</tr>
										<tr>
											<td>P-25</td>
											<td><a href="/assets/javascript:void(0)">Featured image on post types…</a></td>
											<td>22/05/2015</td>
											<td><span class="label label-success">Low</span></td>
											<td>Updated</td>
										</tr>
										<tr>
											<td>P-10</td>
											<td><a href="/assets/javascript:void(0)">Multiple feed fixes and…</a></td>
											<td>17/05/2015</td>
											<td><span class="label label-warning">Medium</span></td>
											<td>Open</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default">Go to task manager</button>
							</div>
						</div>
					</div>
				</div>

			</header>
            <div id="contentwrapper">
                <div class="main_content">
<div id="jCrumbs" class="breadCrumb module">
    <ul>
        <li>
            <a href="javascript:void(0);"><i class="glyphicon glyphicon-home"></i></a>
        </li>
        <li>
            Supplier Registration Form 
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
		{{-- <h3 class="heading">Wizard with validation</h3> --}}
		<div class="row">
			<div class="col-sm-12 col-md-12">
                <form id="validate_wizard" class="stepy-wizzard form-horizontal">

					@csrf
                    <fieldset title="Contract info">
						<legend class="hide">Email Validation & OTP</legend>
						
						{{-- <div class="formSep form-group">
							<label for="v_registration" id="v_registration" class="col-md-2 control-label">Registration Type :</label>
						<div class="col-md-10"> --}}
							{{-- <label class="radio-inline"><input type="radio" name="optionsRadios" id="company_id" checked>Company</label>
							<label class="radio-inline"><input type="radio" name="optionsRadios" id="individual_id">Individual</label> --}}
						{{-- </div>
					</div> --}}
					
						<section id="firstForm">

							<div class="formSep form-group">
								<label for="v_company_name" id="v_company_name" class="col-md-2 control-label">Company Name :</label>
								<div class="col-md-10">
									<input type="text" name="v_username" id="v_username" class="input-sm form-control"  required>
								</div>
							</div>


							<div class="formSep form-group">
								<label for="" id="" class="col-md-2 control-label">Telephone:</label>

								<div class="col-md-10">
								
								<div class="col-md-2 mt-0 pt-0 mr-0 pr-0" style="padding:0; margin:0;">
									{{-- <input type="text" name="code3" id="code4" class="input-sm form-control" value="+260"> --}}

									<select name="" id=""  class="input-sm form-control">		
										<option data-countryCode="GB" value="260" >Zambia</option>
										@foreach ($countrylist as $country)
										<option data-countryCode="GB" value={{$country->PhoneCode}} >{{$country->Name}}</option>
										@endforeach
									</select>

								</div>

								<div class="col-md-9" style="padding:0; margin:0;">
									<input type="text" name="v_telephone" id="v_telephone" class="input-sm form-control">
								</div>
							</div>
							</div>



							<div class="form-group">
								<label for="v_email" class="col-md-2 control-label">E-mail:</label>
								<div class="col-md-10">
									<input type="text" name="v_email" id="v_email" class="input-sm form-control" placeholder="Enter email address" required>

									@error('v_email')
										<label class="text-danger">{{message}}</label>
									@enderror
								</div>
							</div>		
							

							<div class="formSep form-group">
								<label for="" id="" class="col-md-2 control-label">Captcha :</label>
								<div class="col-md-10">
									<input type="text" name="captcha_code" id="captcha_code" class="input-sm form-control" placeholder="Enter captcha code" required>
                                    <span class="text-danger">@error('captcha_code'){{$message}}@enderror</span>
                                </div>
							</div>


							
                            <div class="formSep form-group">
								<label for="" id="" class="col-md-2 control-label">Enter this captcha :</label>
                                    <div class="col-md-10">
                                        <div class="captcha"> 
                                       		 <span>{!! captcha_img('flat') !!}</span>
                                       		 <button type="submit" class="btn btn-danger reload" id="reload">&#x21bb;</button>  
										</div> 
                                    </div>
							 </div>

							
							<div>
								<button  id="validateEmail" type="button" class="btn btn-primary">
									Validate Email <i class="glyphicon glyphicon-check"></i>
								</button>
								
								<br> <br>

								<p>If you already have an account, Please <a href="{{route('supplier-login')}}">Login Now</a></p>
							</div>
						</section>
						<section id="otpForm">
							<div>
								

								<label class="">One Time Password</label> 
								<button class="btn btn-success" id="regenerate_otp">Regenerate new OTP</button>  

								<button   type="button" class="btn btn-warning" id="redirectBack">
									Change Email <i class="glyphicon glyphicon-chevron-right"></i>
									</button>
								<br> <br>
								<label class=""> <span style="font-weight: bold" id="otpMessage"></span></label>
							</div>
							<div class="form-group">
								<label for="otp_token" class="col-md-2 control-label">Enter OTP</label>
								<div class="col-md-10">
									<input type="number" name="otp_token" id="otp_token" class="input-sm form-control" required>
								</div>
							</div>	

							<h6 style="font: bold;color:red;" id="otpExpiry"></h6>

							<div>
								<button  id="nextText checkOTP" type="button" class="btn btn-primary" onclick="verifyOTPZ()">
									Submit <i class="glyphicon glyphicon-chevron-right"></i>
								</button>
							</div>
						</section>
                    </fieldset>

					<fieldset title="Supplier Details">
						<div class="tabbable tabs-left">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab1" data-toggle="tab">1. Login Credentials</a></li>
								<li><a href="#tab5" data-toggle="tab">2. Business Information</a></li>
								<li><a href="#tab2" data-toggle="tab">3. Financial Information</a></li>
								<li><a href="#tab3" data-toggle="tab">4. Capacity</a></li>
								<li><a href="#tab4" data-toggle="tab">5. Required Documents</a></li>
								
							</ul>


							<div class="tab-content">
								<div class="tab-pane active" id="tab1">
									<h2 class="heading padMarg" style="font-weight: bold">1. Account Login Credentails :</h2> 
									<div class="formSep form-group">

										@if (Session::get('fail'))
										<div class="alert alert-danger">
											{{Session::get('fail')}}
										</div>
									@endif
						
								<div class="RowCollection">
									<div class="col-md-3">
										<label for="" class="boldTitle padMarg" class="padMarg">Username :</label>
										<input type="text" name="login_username" id="login_username" class="input-sm form-control" required>
										{{-- <span class="alert alert-danger">@error('login_username'){{$message}}@enderror</span> --}}
									</div>
		
									<div class="col-md-3">
										<label for="" class="boldTitle padMarg" class="padMarg">Password :</label>
										<input type="password" name="login_password" id="login_password" class="input-sm form-control" required>
										{{-- <span class="alert alert-danger">@error('login_password')@enderror</span> --}}
										
									</div>

									<div class="col-md-3">
										<label for="" class="boldTitle padMarg" class="padMarg">Confirm password :</label>
										<input type="password" name="confirm_password" id="confirm_password" class="input-sm form-control" required>
										{{-- <span class="alert alert-danger">	@error('confirm_password'){{$message}}@enderror</span> --}}
									</div>

									<input type="hidden" id="email_verified_already_id">

									<input type="hidden" id="original_supplier_email">
		
									<div class="col-md-3">
										<label for="" class="boldTitle padMarg" class="padMarg" hidden>Supplier Reference Number :</label>
										<input type="hidden" name="supplier_reference_number" id="supplier_reference_number" class="input-sm form-control" required>
										{{-- <span class="alert alert-danger">	@error('supplier_reference_number'){{$message}}	@enderror</span> --}}
									</div>
								</div>

								</div>
							</div>
								
							<div class="tab-pane" id="tab2">
									
								<h2 class="heading padMarg" style="font-weight: bold">3.Financial Information</h2>          
							
								<div class="formSep form-group">
								
									<div class="col-md-3">
										<label for="" class="boldTitle padMarg">Account name :</label>
										<input type="text" name="Account_name" id="Account_name" class="input-sm form-control" required>
									</div>

										<div class="col-md-3">
											<label for="" class="boldTitle padMarg">Bank name :</label>
											<input type="text" name="Bank_name" id="Bank_name" class="input-sm form-control" required>
										</div>

										<div class="col-md-3">
											<label for="" class="boldTitle padMarg">Bank Account number :</label>
											<input type="text" name="Bank_Account" id="Bank_Account" class="input-sm form-control" required>
										</div>

										<div class="col-md-3">
											<label for="" class="boldTitle padMarg">Bank Branch :</label>
											<input type="text" name="Bank_Branch" id="Bank_Branch" class="input-sm form-control" required>
										</div>

										<div class="RowCollection">
											<div class="col-md-3">
												<label for="" class="boldTitle padMarg" class="padMarg">Branch code :</label>
												<input type="text" name="Branch_code" id="Branch_code" class="input-sm form-control" required>
											</div>
				
											<div class="col-md-3">
												<label for="" class="boldTitle padMarg" class="padMarg">Account currency :</label>
												<input type="text" name="Account_currency" id="Account_currency" class="input-sm form-control" required>
											</div>
											
				
											<div class="col-md-3 padMarg">
												<label for="" class="boldTitle " >Company financial contact person </label>
												<input type="text" name="company_financial_contact" id="company_financial_contact" class="input-sm form-control" required>
											</div>

											<div class="col-md-3">
												<label for="" class="boldTitle padMarg" class="padMarg">Contact person email :</label>
												<input type="eamail" name="contact_person_email" id="contact_person_email" class="input-sm form-control" required>
											</div>

										</div>

										<div class="RowCollection">

											<div class="col-md-4 mt-3 pt-4 padMarg">
												<label for="" class="boldTitle">Contact person phone number : </label>
												<div class="col-md-2 mt-0 pt-0 mr-0 pr-0" style="padding:0; margin:0;">
													<input type="text" name="code3" id="code4" class="input-sm form-control" value="+256">
												</div>
												<div class="col-md-10" style="padding:0; margin:0;">
													<input type="text" name="contact_person_phone_number" id="contact_person_phone_number" class="input-sm form-control">
												</div>
											</div>					
										</div>
								</div>

							</div>

{{-- 						
								<div class="tab-pane" id="tab2">
									
									<h2 class="heading padMarg" style="font-weight: bold">2.Financial Information</h2>          
								
									<div class="formSep form-group">
									
										<div class="col-md-3">
											<label for="" class="boldTitle padMarg">Account name :</label>
											<input type="text" name="Account_name" id="Account_name" class="input-sm form-control" required>
										</div>

											<div class="col-md-3">
												<label for="" class="boldTitle padMarg">Bank name :</label>
												<input type="text" name="Bank_name" id="Bank_name" class="input-sm form-control" required>
											</div>

											<div class="col-md-3">
												<label for="" class="boldTitle padMarg">Bank Account number :</label>
												<input type="text" name="Bank_Account" id="Bank_Account" class="input-sm form-control" required>
											</div>

											<div class="col-md-3">
												<label for="" class="boldTitle padMarg">Bank Branch :</label>
												<input type="text" name="Bank_Branch" id="Bank_Branch" class="input-sm form-control" required>
											</div>

											<div class="RowCollection">
												<div class="col-md-3">
													<label for="" class="boldTitle padMarg" class="padMarg">Branch code :</label>
													<input type="text" name="Branch_code" id="Branch_code" class="input-sm form-control" required>
												</div>
					
												<div class="col-md-3">
													<label for="" class="boldTitle padMarg" class="padMarg">Account currency :</label>
													<input type="text" name="Account_currency" id="Account_currency" class="input-sm form-control" required>
												</div>
												
					
												<div class="col-md-3 padMarg">
													<label for="" class="boldTitle " >Company financial contact person </label>
													<input type="text" name="company_financial_contact" id="company_financial_contact" class="input-sm form-control" required>
												</div>

												<div class="col-md-3">
													<label for="" class="boldTitle padMarg" class="padMarg">Contact person email :</label>
													<input type="eamail" name="contact_person_email" id="contact_person_email" class="input-sm form-control" required>
												</div>

											</div>

											<div class="RowCollection">

												<div class="col-md-4 mt-3 pt-4 padMarg">
													<label for="" class="boldTitle">Contact person phone number : </label>
													<div class="col-md-2 mt-0 pt-0 mr-0 pr-0" style="padding:0; margin:0;">
														<input type="text" name="code3" id="code4" class="input-sm form-control" value="+256">
													</div>
													<div class="col-md-10" style="padding:0; margin:0;">
														<input type="text" name="contact_person_phone_number" id="contact_person_phone_number" class="input-sm form-control">
													</div>
												</div>					
											</div>
	                        		</div>
								</div> --}}

							
								<div class="tab-pane" id="tab3">
									<h2 class="heading padMarg" style="font-weight: bold">1.Economic & financial capacity</h2>

											<div class="formSep form-group">
													<div class="col-md-4">
														<label for="" class="boldTitle padMarg">Annual turnover excluding this contract :</label>
														<input type="number" name="Annual_turnover" id="Annual_turnover" class="input-sm form-control" required>
													</div>

													<div class="col-md-4">
														<label for="" class="boldTitle padMarg">Current assets :</label>
														<input type="number" name="Current_assets" id="Current_assets" class="input-sm form-control" required>
													</div>

													<div class="col-md-4">
														<label for="" class="boldTitle padMarg">Current liabilities :</label>
														<input type="number" name="Current_liabilities" id="Current_liabilities" class="input-sm form-control" required>
													</div>

													<div class="col-md-4">
														<label for="" class="boldTitle padMarg">Current ratio (current assets/current liabilities) :</label>
														<input type="number" name="Current_ratio" id="Current_ratio" class="input-sm form-control" required>
													</div>
											  </div>


											  <h2 class="heading padMarg" style="font-weight: bold">2.Professional capacity</h2>

											<div class="formSep form-group">
													<div class="col-md-4">
											<label for="" class="boldTitle padMarg">No of years in business :</label>
											<input type="number" name="No_of_years_in_business" id="No_of_years_in_business" class="input-sm form-control" required>
										</div>
										<div class="col-md-4">
											<label for="" class="boldTitle padMarg">Number of permanet employees</label>
											<input type="number" name="Number_of_employees" id="Number_of_employees" class="input-sm form-control" required>
										</div>
		
											<div class="col-md-4">
												<label for="" class="boldTitle padMarg">Other employees</label>
												<input type="number" name="Other_employees" id="Other_employees" class="input-sm form-control" required>
											</div>
											  </div>

											  

										<h2 class="heading padMarg" style="font-weight: bold">3.Technical capacity</h2>

											<div class="formSep form-group">
												<div class="mt-4 pt-4 padMarg">
												<div class="col-md-6">
													<label for="" class="boldTitle padMarg" style="margin-top: 7rem !important">a)	Relevant specialisation (state a maximum of 3) :</label>
													{{-- <textarea name="Relevant_specialisation " id="Relevant_specialisation" class="input-sm form-control" cols="30" rows="10" required></textarea> --}}
													<table class="table">
														<thead>
														  <tr>
															<th scope="col">Business Name</th>
															<th scope="col">Specialisation</th>
														  </tr>
														</thead>
														<tbody>
														  <tr>
															<td><input type="text" style="width:100%"></td>
															<td><input type="text" style="width:100%"></td>
														  </tr>
														  <tr>
															<td><input type="text" style="width:100%"></td>
															<td><input type="text" style="width:100%"></td>
														  </tr>
														  <tr>
															<td><input type="text" style="width:100%"></td>
															<td><input type="text" style="width:100%"></td>
														  </tr>
														 
														</tbody>
													  </table>
												</div>

												<div class="col-md-6">
													<label for="" class="boldTitle padMarg">b)	A maximum of 10 Projects/contracts (For each, state: Country projects executed, Overall contract value (US$); Proportion carried out by legal entity; Number of personnel provided; Name of client; and Origin of funding)</label>
													{{-- <input type="number" > --}}
													<textarea name="maximum_of_10_Projects_contracts" id="maximum_of_10_Projects_contracts" class="input-sm form-control" cols="30" rows="10" required></textarea>
												</div>
											</div>
										<div class="formSep form-group">
								</div>
							</div>	
											
						</div>

						<div class="tab-pane" id="tab5">
									
							<h2 class="heading padMarg" style="font-weight: bold">2.Business Details</h2>          
						
							<div class="formSep form-group">
								

										@if (Session::get('fail'))
											<div class="alert alert-danger">
												{{Session::get('fail')}}
											</div>
										@endif
							
									<div class="RowCollection">

										<div class="col-md-3">								
											<label for="" class="boldTitle padMarg">Country :</label>  
													<select name="country" id="country"  class="input-sm form-control">		
														<option data-countryCode="GB" value="260" >Zambia</option>
														@foreach ($countrylist as $country)
														<option data-countryCode="GB" value={{$country->PhoneCode}} >{{$country->Name}}</option>
														@endforeach
													</select>
										</div>

										<div class="col-md-3">
											<label for="" class="boldTitle padMarg">Category :</label>
											<select name="Category" id="Category"  class="input-sm form-control">
												<option value="">Select Category</option>
												@foreach ($categories as $item)
													<option value="{{$item->md_id}}">{{$item->md_name}}</option>
												@endforeach
		
											</select>	
										</div>
		
										<div class="col-md-3">
											<label for="" class="boldTitle padMarg">Sub-category :</label>
											<select name="SubCategory" id="SubCategory"  class="input-sm form-control">
												
											</select>
												
										</div>

										<div class="col-md-3">
											<label for="" class="boldTitle padMarg">Business Name :</label>
											<input type="text" name="BusinessName" id="BusinessName" class="input-sm form-control" required>
										</div>
		
										<div class="padMarg">
		
												<div class="col-md-3 mt-3 pt-4">
													<label for="" class="boldTitle padMarg" class="padMarg">Type of Business :</label>
													<select type="text" name="Type_of_Business" id="Type_of_Business" class="input-sm form-control">
														@foreach ($Type_of_Business as $item)
													<option value="{{$item->md_id}}">{{$item->md_name}}</option>
													@endforeach
		
		
													</select>
												</div>
		
												<div class="col-md-3 mt-3 pt-4">
													<label for="" class="boldTitle padMarg" class="padMarg">Nature of Business :</label>
													<input type="text" name="Nature_of_Business" id="Nature_of_Business" class="input-sm form-control" required>
												</div>
		
												<div class="col-md-3">
													<label for="" class="boldTitle " style="text-align: left">Certificate of Registration Incorporation number :</label>
													<input type="text" name="Certificate_of_Registration" id="Certificate_of_Registration" class="input-sm form-control" required>
												</div>
		
		
												<div class="col-md-3">
													<label for="" class="boldTitle padMarg" >Revenue Authority Taxpayer’s Identification Number</label>
													<input type="text" name="Revenue_Authority_Taxpayers_Identification_Number" id="Revenue_Authority_Taxpayers_Identification_Number" class="input-sm form-control" required>
												</div>
										</div>

										<div class="padMarg">
		
											<div class="col-md-3 mt-3 pt-4">
												<label for="" class="boldTitle " style="text-align: left" id="Tax_Compliance_certificate_expiration_label">Tax compliance certificate expiration date :</label>
												<input type="text" name="Tax_compliance_certificate_expiration" id="Tax_compliance_certificate_expiration" class="input-sm form-control" required>
											</div>
		
											<div class="col-md-3">
												<label for="" class="boldTitle padMarg"  class="padMarg">Physical address :</label>
												<input type="text" name="physical_address" id="physical_address" class="input-sm form-control">
											</div>
		
		
											<div class="col-md-3">
												<label for="" class="boldTitle padMarg" class="padMarg">Company Email :</label>
												<input type="email" name="company_email" id="company_email" class="input-sm form-control" required>
											</div>
										</div>
		
										<div class="padMarg">
											<div class="col-md-3 mt-3 pt-4">
												<label for="" class="boldTitle"  id="National_Pension_Authority_Registration_Number_label">National Pension Authority (NPSA) Registration No : </label>
												<input type="text" name="National_Pension_Authority" id="National_Pension_Authority" class="input-sm form-control" required>
											</div>
		
											<div class="col-md-6 mt-3 pt-4">
												<label for="" class="boldTitle padMarg" style="text-align: left" id="NAPSA_Compliance_Status_certificate_label">NAPSA Compliance Status certificate  :</label>
												<input type="text" name="NAPSA_Compliance_Status_certificate" id="NAPSA_Compliance_Status_certificate" class="input-sm form-control" required>
											</div>
		
		
											<div class="col-md-6 mt-3 pt-4 padMarg">
												<label for="" class="boldTitle">Personal contact :</label>
												<div class="col-md-2 mt-0 pt-0 mr-0 pr-0" style="padding:0; margin:0;">
													<input type="text" name="code1" value="+260" id="code1" class="input-sm form-control" >
												</div>										
												<div class="col-md-10  mt-0 pt-0 ml-0 pl-0" style="padding:0; margin:0;">
													<input type="text" name="contact_person" id="contact_person" class="input-sm form-control"  required>
												</div>
											</div>
										</div>

										<div class="col-md-6 mt-3 pt-4 padMarg">
											<label for="" class="boldTitle">Company Telephone number : </label>
											<div class="col-md-2 mt-0 pt-0 mr-0 pr-0" style="padding:0; margin:0;">
												<input type="text" name="code2" id="code2" class="input-sm form-control" value="+260">
											</div>
											<div class="col-md-10 mt-3 pt-4 " style="padding:0; margin:0;">
												<input type="text" name="company_telephone" id="company_telephone" class="input-sm form-control"  required>
											</div>
										</div>
	
										<div class="col-md-6 mt-3 pt-4 padMarg">
											<label for="" class="boldTitle">Contact telephone number : </label>
											<div class="col-md-2 mt-0 pt-0 mr-0 pr-0" style="padding:0; margin:0;">
												<input type="text" name="code3" id="code3" class="input-sm form-control" value="+260">
											</div>
											<div class="col-md-10" style="padding:0; margin:0;">
												<input type="text" name="contact_person_telephone" id="contact_person_telephone" class="input-sm form-control" required>
											</div>
										</div>
									</div>
							</div>
							</div>
							
							<div class="tab-pane" id="tab4">
								<h2 class="heading padMarg" style="font-weight: bold"4>5.Required Documents </h2>

								<div class="formSep form-group">
										<?php $counter = 1; ?>
										@foreach ($Documents as $document)
											<div class="col-md-6">
												<label for="" class="boldTitle padMarg" class="padMarg">{{$document->md_name}} :</label>
												<input data-name = "{{$document->md_name}}" type="file"  name="attachment<?php echo $counter; ?>" id="attachment<?php echo $counter; ?>" class="input-sm form-control " required>
											</div>
											<?php $counter++; ?>
										@endforeach

									<input type="hidden" value="<?php echo $counter-1; ?>" id="Total_Documents"/>
								  </div>
								</div>
							</div>
							
						</div>
						


		
						<legend class="hide">Provide Supplier Details</legend>

						<div id="backText" class="pull-left">
							<button type="button" class="btn btn-primary">
								<i class="glyphicon glyphicon-chevron-left"></i> Back 
							</button>
						</div>

						<div id="nextText_000" class="pull-right">
							<button type="button" id="Supplier_Registration_Details_" class="btn btn-primary">
								Save and Complete <i class="glyphicon glyphicon-chevron-right"></i>
							</button>
						</div>

                    </fieldset>
				
					


					<fieldset title="Confirm Details">
                        <legend class="hide">Confirm provided Supplier Details</legend>


						<div class="supplier-submitted-details">

						</div>

						<div class="formSep form-group">
                            <label for="v_message" class="col-md-2 control-label">Additional Infomation that may be added <br> (If applicable):</label>
                            <div class="col-md-10">
								<textarea name="v_message" id="v_message" rows="3" class="form-control"></textarea>
							</div>
                        </div>
		
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<div class="row">
									<div class="col-sm-12 col-md-12">
										<h3 class="heading">SELF-DECLARATION </h3>
										<div id="accordion1" class="panel-group accordion">
											<div class="panel panel-default">
												<div class="panel-heading">
													<a href="#collapseOne" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle collapsed">
														Please Read & Confirm these Regulations : #1
													</a>
												</div>
												<div class="panel-collapse collapse" id="collapseOne">
													<div class="panel-body" style="font-size: 1em;">


														<ol type="a">
										
														<p><li>	I/We are nationals of  <span id="db_country"> </span>  therefore eligible and that I/We have legal capacity to enter into contract and have sufficient experience to undertake this assignment;</li></p>
														
														<p><li>	The information given above is true and further state that I/We also understand that payment of any required fees does not guarantee inclusion into the supplier database;</li></p>
														
														<p><li>	I/We are not insolvent/in receivership, bankrupt or wound up, business activities not suspended/not subject to legal proceedings. I/We/ have not been convicted of offences concerning professional conduct by a judgment which have the force of res judicata; (i.e. against which no appeal is possible);</li></p>
														
														<p><li>	I/We have not been the subject of a judgment which has the force of res judicata for fraud, corruption, involvement in a criminal organisation or any other illegal activity detrimental to the COMESA Secretariat's financial interests; or are not being currently subject to an administrative penalty;</li></p>
														
														<p><li>	if the legal, technical, financial position, or the contractual capacity of the firm changes, we commit ourselves to inform you and acknowledge your sole right to review the pre-qualification made;</li></p>
														
														<p><li>	I/We understand that I/We shall be disqualified should the information submitted here for purpose of seeking qualification be materially inaccurate or materially incomplete;</li></p>
														
														<p><li>	I/We give COMESA the authority to seek any other references concerning my/Our Company from whatever sources deemed relevant; </li></p>
														
														<p><li>	if pre-qualified, I/we undertake to participate in submission of a tender or quotation when called upon to do so;</li></p>
														
														<p><li>	I/We have fulfilled obligations related to the payments of social security contributions and the payment of taxes in accordance with the legal provisions of the country in which I am/we are established or with those countries where the contract is to be performed;</li></p>
														
														<p><li>	I/We are aware that, for the purposes of safeguarding the COMESA’s financial interests, our personal data may be transferred to internal audit services, of COMESA</li></p>
														
														<p><li>	I/We undertake, if required, to provide the proof usual under the law of the country in which we are effectively established that we do not fall into any of the exclusion situations. The date on the evidence or documents provided will be no earlier than 1 year before the date of submission of the tender and, in addition, we will provide a statement that our situation has not altered in the period which has elapsed since the evidence in question was drawn up.</li></p>
														
														<p><li>	I/We also undertake, if required, to provide evidence of our financial and economic standing and our technical and professional capacity according to the requirements of this call for Prequalification</li></p>
														<br>
				
															
														<input type="checkbox" id="Acceept_rules_and_regulations"  required> &nbsp; <label for="Acceept_rules_and_regulations" style="display: inline">Read and accept the above rules and regulations before you submit your Application</label> 
															
														<input type="hidden" value="hello" id="unique_id_db">
														<input type="hidden" id="_pass_">
														</ol>
													</div>
												</div>
											</div>
											
										</div>
									</div>
									
								</div>
							</div>
						</div>      
						
						<div id="backText">
							<button type="button" class="btn btn-primary">
								<i class="glyphicon glyphicon-chevron-left"></i> Back 
							</button>

							<button type="button" class="btn btn-primary pull-right" id="final_submission"><i class="glyphicon glyphicon-ok"></i> Send registration</button>
						</div>
						
						{{-- <button class="finish"></button> --}}
					</fieldset>

					<input type="hidden" name="" class="finish">
				</form>
				

				
				<script type="text/javascript">
				//  Credential validation before complete

				$('#reload').click(function (){
                      $.ajax({
      
                          type:'GET',
                          url:'/reload-captcha',
                          success:function(data){
                              $(".captcha span").html(data.captcha)
                          }
                      });
                  });



				$(document).ready(function(){
						$('#final_submission').click(function(){

							var check = false;
							if($('#Acceept_rules_and_regulations').prop('checked') == true ){
								check = $('#Acceept_rules_and_regulations').val();
							}

							if(check == 0){
								alert("You have to accept the rules and regulations before you submit");
								return ;
							}


							var _pass_ = $('#_pass_').val();
							var user_id = $('#unique_id_db').val();
							var original_supplier_email = $('#original_supplier_email').val();

							

							var form_data = new FormData();

							form_data.append('_pass_', _pass_);
							form_data.append('user_id', user_id);
							form_data.append('original_supplier_email', original_supplier_email);

							$.ajax({
								type: "post",
								processData: false,
								contentType: false,
								cache: false,
								data		: form_data,								
								headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},

								url			:'/update-and-submit',
								success		:function(data){
									if(data.status){
										alert(data.message);
										location.replace('/userdata/'+data.user_id);
									}
								},
								error: function(data)
								{
									$('body').html(data.responseText);
								}
							});
						});
				});


		
					$(document).ready(function(){
						$('#validateEmail').click(function(){

							$('#validateEmail').attr('disabled','false');
							$('#validateEmail').html('Loading...');

							
							var username = $('#v_username').val();
							var telephone = $('#v_telephone').val();
							var email = $('#v_email').val();
							var captcha_code = $('#captcha_code').val();


							// alert(captcha_code);

							if(username  == "" || telephone == "" || email == "" || captcha_code == ""){
								alert("Missing Form fields");
								location.replace('/supplier-registration/');
							}
							
							
							var form_data = new FormData();
							form_data.append('username', username);
							form_data.append('telephone', telephone);
							form_data.append('email', email);
							form_data.append('otp_token', "4523");
							form_data.append('captcha_code',captcha_code);
											
							
							$.ajax({
								type: "post",
								processData: false,
								contentType: false,
								cache: false,
								data		: form_data,								
								headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},

								url			:'/generate-otp',
								success		:function(data){

									if(data.status){
										if(data.verification){
											$("#email_verified_already_id").val(data.email_verified_already);
											$('form').stepy('step',2);
										}

										if(data.Acceptance){
										alert('This email has already finished submitting an Application, please login into your Supplier Portal');
											location.href = "{{url('supplier-login')}}";
										}
										$('#firstForm').hide();
										$('#otpForm').show();
										$('#otpMessage').html(data.message);
										$("#original_supplier_email").val(data.original_email);
									}
									else{		
										alert(data.invalid_captcha);								
										$('#firstForm').show();
										$('#otpForm').hide();
										location.replace('/supplier-registration');
									}
									
								},
								error: function(data)
								{
									$('body').html(data.responseText);
								}
							});
						});
					});

					//Regenerate OTP token

					$(document).ready(function(){
						$('#regenerate_otp').click(function(){

							$('#regenerate_otp').html('Regenerating...');
							$('#regenerate_otp').attr('disabled', true);

							var email = $('#v_email').val();
							
							var form_data = new FormData();
							form_data.append('email', email);

							$.ajax({
								type: "post",
								processData: false,
								contentType: false,
								cache: false,
								data		: form_data,								
								headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},

								url			:'/regenerate-otp',
								success		:function(data){
									if(data.status){
										$('#regenerate_otp').hide();
										$('#OTP_sent').show();
										alert(data.message);
									}else{										
										$('#regenerate_otp').show();
									}
									
								},
								error: function(data)
								{
									alert(data);
								}
							});
						});
					});

					//Verify OTP Token

					function verifyOTPZ()
					{
						
						var email = $('#v_email').val();
						var otp_token = $('#otp_token').val();

							$('#checkOTP').attr('disabled','false');
							$('#checkOTP').html('Loading...');
							
							var form_data = new FormData();
							form_data.append('email', email);

							
							form_data.append('otp_token', otp_token);
							
							$.ajax({
								type: "post",
								processData: false,
								contentType: false,
								cache: false,
								data		: form_data,								
								headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},

								url			:'/fetch-email',
								success		:function(data){
									
									var values = data;//JSON.parse(data);
									if(values.status){
										alert(values.message);
										$('form').stepy('step',2);
									}else{
										alert(values.message);
									}
								},
							});
						}

							$(document).ready(function(){
						$('#redirectBack').click(function(){
							location.href = "{{url('supplier-registration')}}";
						});
					});

					$(document).ready(function(){
					$('input[type=radio][name=optionsRadios]').change(function() {
					if(this.id == 'individual_id') {
						$("#v_company_name").text("Supplier Name :");
					}
					else
					{
						$("#v_company_name").text("Company Name :");
					}
				});
			});


					// change radio button 
		// 			$(document).ready(function(){

		// 				$('input[type=radio][name=individual_name_supplier]').change(function() {
		// 				if (this.value == 'individual_supplier') {
		// 					$("#v_company_name").text("Supplier Name :");
				
		// 				}

		// 			});
		// 		});

		// 		$(document).ready(function(){

		// 		$('input[type=radio][name=individual_name_supplier]').change(function() {
		// 		if(this.value == 'company_supplier') {
		// 			$("#v_company_name").text("Company Name :");
		// 		}
		// 	});
		// });



					// Collect Supplier Details:

					$(document).ready(function(){
						$('#Supplier_Registration_Details_').click(function(){

							//$('#Supplier_Registration_Details').html('Loading...');


							var login_username = $('#login_username').val();
							// alert(login_username);
							var login_password = $('#login_password').val();
							var confirm_password = $('#confirm_password').val();
							// var supplier_reference_number = $('#supplier_reference_number').val();
							var original_supplier_email = $('#original_supplier_email').val();
							var email_verified_already_id = $('#email_verified_already_id').val();
							

							var country = $('#country').val();
							var Category = $('#Category').val();
							var SubCategory = $('#SubCategory').val();
							var Type_of_Business = $('#Type_of_Business').val();
							var Nature_of_Business = $('#Nature_of_Business').val();
							var BusinessName = $('#BusinessName').val();
							var Certificate_of_Registration = $('#Certificate_of_Registration').val();
							var Tax_compliance_certificate_expiration = $('#Tax_compliance_certificate_expiration').val();
							var Revenue_Authority_Taxpayers_Identification_Number = $('#Revenue_Authority_Taxpayers_Identification_Number').val();
							var company_email = $('#company_email').val();
							var physical_address = $('#physical_address').val();
							var National_Pension_Authority = $('#National_Pension_Authority').val();
							var NAPSA_Compliance_Status_certificate = $('#NAPSA_Compliance_Status_certificate').val();
							var contact_person = $('#contact_person').val();
							var company_telephone = $('#company_telephone').val();
							var contact_person_telephone = $('#contact_person_telephone').val();
							var Account_name = $('#Account_name').val();
							var Bank_name = $('#Bank_name').val();
							var Bank_Account = $('#Bank_Account').val();
							var Bank_Branch = $('#Bank_Branch').val();
							var Branch_code = $('#Branch_code').val();
							var Account_currency = $('#Account_currency').val();
							var company_financial_contact = $('#company_financial_contact').val();
							var contact_person_email = $('#contact_person_email').val();
							var contact_person_phone_number = $('#contact_person_phone_number').val();
							var Annual_turnover = $('#Annual_turnover').val();
							var Current_assets = $('#Current_assets').val();
							var Current_liabilities = $('#Current_liabilities').val();
							var Current_ratio = $('#Current_ratio').val();
							var Relevant_specialisation  = $('#Relevant_specialisation ').val();
							var maximum_of_10_Projects_contracts  = $('#maximum_of_10_Projects_contracts').val();
							var No_of_years_in_business  = $('#No_of_years_in_business').val();
							var Number_of_employees  = $('#Number_of_employees ').val();
							var Other_employees  = $('#Other_employees').val();


							// Attachment Documents 
							var form_data = new FormData();
							var errors = new Array();

							var Total_Documents = $('#Total_Documents').val();


							for(var i=1; i<=Total_Documents; i++){
								if(!$('#attachment'+i).val()){
									errors.push('Attach '+$('#attachment'+i).attr('data-name'));
								}else{
									form_data.append('attachment'+i, $('#attachment'+i).prop('files')[0]);
								}
							}
							
							if( Category  == "" ){
								errors.push("Category Field is required");
							}
							if(SubCategory == ""){
								errors.push("Sub category Field is required");								
							}

							if(Type_of_Business == ""){
								errors.push("Type of Business Field is required");								
							}

							if(Nature_of_Business == ""){
								errors.push("Nature of Field is required");
							
							}

							if(BusinessName == ""){
								errors.push("Business Field is required");								
							}

							if(Certificate_of_Registration == ""){
								errors.push("Ceritificate of Registration Field is required");								
							}

							if(Tax_compliance_certificate_expiration == ""){
								errors.push("Tax compliance Field is required");								
							}

							if(Revenue_Authority_Taxpayers_Identification_Number == ""){
								errors.push("Revenue Authority Taxpayers Identification Number Field is required");								
							}

							if(company_email == ""){
								errors.push("Company Email Field is required");								
							}

							if(physical_address == ""){
								errors.push("Physical Address Field is required");								
							}


							if(National_Pension_Authority == ""){
								errors.push("National Pension Authority Field is required");								
							}

							if(NAPSA_Compliance_Status_certificate == ""){
								errors.push("NAPSA Field is required");								
							}

							if(contact_person == ""){
								errors.push("Contact Person Field is required");								
							}


							if(company_telephone == ""){
								errors.push("Company Telephone Field is required");								
							}

							if(contact_person_telephone == ""){
								errors.push("Contact Person Telephone Field is required");								
							}

							if(Account_name == ""){
								errors.push("Account Name Field is required");								
							}

							if(Bank_name == ""){
								errors.push("Bank Name Field is required");								
							}

							if(Bank_Account == ""){
								errors.push("Bank Account Field is required");								
							}

							if(Bank_Branch == ""){
								errors.push("Bank Branch Field is required");								
							}
							if(Branch_code == ""){
								errors.push("Branch Code Field is required");								
							}
							if(Account_currency == ""){
								errors.push("Account Currency Field is required");								
							}
							if(company_financial_contact == ""){
								errors.push("Company Financial Contact Field is required");								
							}
							if(contact_person_email == ""){
								errors.push("Contact person Email Field is required");								
							}
							if(contact_person_phone_number == ""){
								errors.push("contact person phone number Field is required");								
							}
							if(Annual_turnover == ""){
								errors.push("Annual Turnover Field is required");								
							}
							if(Current_assets == ""){
								errors.push("Current Assets Field is required");								
							}
							if(Current_liabilities == ""){
								errors.push("Current liabilities Field is required");								
							}
							if(Current_ratio == ""){
								errors.push("Current Ratio Field is required");								
							}
							if(Relevant_specialisation == ""){
								errors.push("Relevant Specialisation Field is required");								
							}
							if(maximum_of_10_Projects_contracts == ""){
								errors.push("Maximum of 10 projects Field is required");								
							}
							if(No_of_years_in_business == ""){
								errors.push("Number of years Field is required");								
							}
							if(Number_of_employees == ""){
								errors.push("Number of Employees Field is required");								
							}
							if(Other_employees == ""){
								errors.push("Other Employess Field is required");								
							}

							if(login_username == ""){
								errors.push("Login username Field is required");								
							}
							if(login_password == ""){
								errors.push("Password Field is required");								
							}
							if(confirm_password == ""){
								errors.push("Confirm password Field is required");								
							}
							// if(supplier_reference_number == ""){
							// 	errors.push("Supplier Reference number Field is required");								
							// }

								
											
							
							if(errors.length){
								swal('Error',errors.join('\n'),'error');
								$('#Supplier_Registration_Details').attr('disabled','false');
								return false;
							}


							form_data.append('Total_Documents', Total_Documents);


							form_data.append('login_username', login_username);
							form_data.append('login_password', login_password);
							form_data.append('confirm_password', confirm_password);
							// form_data.append('supplier_reference_number', supplier_reference_number);
							form_data.append('original_supplier_email', original_supplier_email);
							form_data.append('email_verified_already_id', email_verified_already_id);


							form_data.append('country', country);
							form_data.append('Category', Category);
							form_data.append('SubCategory', SubCategory);
							form_data.append('Type_of_Business', Type_of_Business);
							form_data.append('Nature_of_Business', Nature_of_Business);
							
							form_data.append('BusinessName', BusinessName);
							form_data.append('Certificate_of_Registration', Certificate_of_Registration);
							form_data.append('Tax_compliance_certificate_expiration', Tax_compliance_certificate_expiration);
							form_data.append('Revenue_Authority_Taxpayers_Identification_Number', Revenue_Authority_Taxpayers_Identification_Number);
							form_data.append('company_email', company_email);
							form_data.append('physical_address', physical_address);
							form_data.append('National_Pension_Authority', National_Pension_Authority);
							form_data.append('NAPSA_Compliance_Status_certificate', NAPSA_Compliance_Status_certificate);
							form_data.append('contact_person', contact_person);
							form_data.append('company_telephone', company_telephone);
							form_data.append('contact_person_telephone', contact_person_telephone);
							form_data.append('Account_name', Account_name);
							form_data.append('Bank_name', Bank_name);
							form_data.append('Bank_Account', Bank_Account);
							form_data.append('Bank_Branch', Bank_Branch);
							form_data.append('Branch_code', Branch_code);
							form_data.append('Account_currency', Account_currency);
							form_data.append('company_financial_contact', company_financial_contact);
							form_data.append('contact_person_email', contact_person_email);
							form_data.append('contact_person_phone_number', contact_person_phone_number);
							form_data.append('Annual_turnover', Annual_turnover);
							form_data.append('Current_assets', Current_assets);
							form_data.append('Current_liabilities', Current_liabilities);
							form_data.append('Current_ratio', Current_ratio);
							form_data.append('Relevant_specialisation', Relevant_specialisation);
							form_data.append('Number_of_employees', Number_of_employees);
							form_data.append('maximum_of_10_Projects_contracts', maximum_of_10_Projects_contracts);
							form_data.append('No_of_years_in_business', No_of_years_in_business);
							form_data.append('Other_employees', Other_employees);
							
							$.ajax({
								type: "post",
								processData: false,
								contentType: false,
								cache: false,
								data		: form_data,								
								headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
								url			:'/SupplierRegistration-Details',
								success		:function(data){
									if(data.status){							
										$('form').stepy('step',3);
										$('.supplier-submitted-details').html(data.details);
										$('#db_country').html(data.submited_country);
									    $("#unique_id_db").val(data.unique_id);
										// $("#_pass_").val(data.pass_abc);
										
									}else{										
										$('#firstForm').show();
										$('#otpForm').hide();
									}								
								},
								error: function(data)
								{
									$('body').html(data.responseText);
								}
							});
						});
					});

					
					$(document).ready(function(){
					$("#country").change(function(){

					var country = $(this).val();
				
					var form_data = new FormData();

					form_data.append('country', country);
					
					$.ajax({
								type: "post",
								processData: false,
								contentType: false,
								cache: false,
								data		: form_data,	
								url			:'Countries',							
								headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
								success		:function(data){
									if(data.status){
										
										$('#code1').attr('value', data.changeCode)
										$('#code2').attr('value', data.changeCode)
										$('#code3').attr('value', data.changeCode)
										$('#code4').attr('value', data.changeCode)
										// $('#Tax_Compliance_no_label').attr('value', data.changeCode)

										var zambia_code = data.changeCode;
										if(zambia_code == 260){

											$("#Tax_Compliance_no_label").text("Zambia Revenue Authority (ZRA) Taxpayer’s Identification Number (TPIN)");

											$("#Tax_Compliance_certificate_expiration_label").text("ZRA Tax compliance certificate expiration date");

											$("#NAPSA_Compliance_Status_certificate_label").text("NAPSA Compliance Status certificate :");

											$("#National_Pension_Authority_Registration_Number_label").text("National Pension Authority (NPSA) Registration No :");
										}
										else{
											$("#Tax_Compliance_no_label").text("Revenue Authority Taxpayer’s Identification Number");
											$("#Tax_Compliance_certificate_expiration_label").text("Tax compliance certificate expiration date :");
											$("#NAPSA_Compliance_Status_certificate_label").text(" Compliance Status certificate :");
											$("#National_Pension_Authority_Registration_Number_label").text("National Pension Authority Registration No :");
										}

									}else{										
										$('#firstForm').show();
										$('#otpForm').hide();
									}
								}
					});
				});
				});


				$(document).ready(function(){
					$("#Category").change(function(){

					var Category = $(this).val();

					var form_data = new FormData();

					form_data.append('Category', Category);
					
					$.ajax({
								type: "post",
								processData: false,
								contentType: false,
								cache: false,
								data		: form_data,	
								url			:'/subcategories',							
								headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
								success		:function(data){
								
									$('#SubCategory').html(data);
								}
					});
				});
			});
	 </script>

				
								</div>
							</div>
						</div>
					</div>  
              </div>
            </div>
        </div>

    <a href="/assets/javascript:void(0)" class="sidebar_switch on_switch bs_ttip" data-placement="auto right" data-viewport="body" title="Hide Sidebar">Sidebar switch</a>

    @include('includes.supplier-side-bar')

    <script src="/assets/js/jquery-migrate.min.js"></script>
    <script src="/assets/lib/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
    <!-- touch events for jquery ui-->
	<script src="/assets/js/forms/jquery.ui.touch-punch.min.js"></script>
    <!-- easing plugin -->
	<script src="/assets/js/jquery.easing.1.3.min.js"></script>
    <!-- smart resize event -->
	<script src="/assets/js/jquery.debouncedresize.min.js"></script>
    <!-- js cookie plugin -->
	<script src="/assets/js/jquery_cookie_min.js"></script>
    <!-- main bootstrap js -->
	<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- bootstrap plugins -->
	<script src="/assets/js/bootstrap.plugins.min.js"></script>
	<!-- typeahead -->
	<script src="/assets/lib/typeahead/typeahead.min.js"></script>
    <!-- code prettifier -->
	<script src="/assets/lib/google-code-prettify/prettify.min.js"></script>
    <!-- sticky messages -->
	<script src="/assets/lib/sticky/sticky.min.js"></script>
    <!-- lightbox -->
	<script src="/assets/lib/colorbox/jquery.colorbox.min.js"></script>
    <!-- jBreadcrumbs -->
	<script src="/assets/lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
	<!-- hidden elements width/height -->
	<script src="/assets/js/jquery.actual.min.js"></script>
	<!-- custom scrollbar -->
	<script src="/assets/lib/slimScroll/jquery.slimscroll.js"></script>
	<!-- fix for ios orientation change -->
	<script src="/assets/js/ios-orientationchange-fix.js"></script>
	<!-- to top -->
	<script src="/assets/lib/UItoTop/jquery.ui.totop.min.js"></script>
	<!-- mobile nav -->
	<script src="/assets/js/selectNav.js"></script>
    <!-- moment.js date library -->
    <script src="/assets/lib/moment/moment.min.js"></script>

	<!-- common functions -->
	<script src="/assets/js/pages/gebo_common.js"></script>

	<!-- validation -->
    <script src="/assets/lib/validation/jquery.validate.min.js"></script>
    <!-- wizard -->
    <script src="/assets/lib/stepy/js/jquery.stepy.min.js"></script>
    <!-- wizard functions -->
    <script src="/assets/js/pages/gebo_wizard.js"></script>

    </body>
</html>
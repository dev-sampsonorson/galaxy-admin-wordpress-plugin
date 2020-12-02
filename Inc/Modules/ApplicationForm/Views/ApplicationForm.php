<h1>Application Form</h1>
<button id="btnJustClick" type="button" class="btn btn-primary">Just Click</button>
<div id="student-application-notification" class="alert alert-dismissible fade d-none" role="alert">
        <div class="alert-message"></div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<div id="student-reg-form" class="c-wizard">
    <div class="c-wizard-nav c-wizard__nav">
        <div data-section-name="purchases" data-section-index="0" class="c-wizard-nav__step">
            <div class="c-wizard-nav__indicator">
                <span class="c-wizard-nav__content">1</span>
            </div>
            <span class="c-wizard-nav__description">Purchases</span>
        </div>
        <div class="c-wizard-nav__line"></div>
        <div data-section-name="personal-info" data-section-index="1" class="c-wizard-nav__step">
            <div class="c-wizard-nav__indicator">
                <span class="c-wizard-nav__content">2</span>
            </div>
            <span class="c-wizard-nav__description">Personal Information</span>
        </div>
        <div class="c-wizard-nav__line"></div>
        <div data-section-name="guardian-info" data-section-index="2" class="c-wizard-nav__step">
            <div class="c-wizard-nav__indicator">
                <span class="c-wizard-nav__content">3</span>
            </div>
            <span class="c-wizard-nav__description">Guardian</span>
        </div>
        <div class="c-wizard-nav__line"></div>
        <div data-section-name="done" data-section-index="3" class="c-wizard-nav__step">
            <div class="c-wizard-nav__indicator">
                <span class="c-wizard-nav__content">4</span>
            </div>
            <span class="c-wizard-nav__description">Done</span>
        </div>
    </div>
    <form id="applicationForm" class="c-wizard-form c-wizard__form" data-parsley-errors-messages-disabled data-url="<?php echo admin_url('admin-ajax.php'); ?>"><!--  -->
        <div class="c-wizard-form__content">
            <div data-section-name="purchases" class="c-wizard-form__section">
                <p class="c-wizard-form__info">
                    What services or products may we offer you? Also let us know your email so we could contact you.
                </p>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="txtEmail">Email Address</label>
                        <input type="email" class="form-control form-control-sm" id="txtEmail" placeholder="Email Address" required=""><!-- -->
                    </div>
                </div>
            
                <div class="c-exam-list" data-parsley-min-purchase data-parsley-group="block-0">
                    <span class="c-exam-list__error-desc">Choose at least one service for the exams listed</span>
                    <div class="list-group">
                        <!-- Dynamically Injected -->
                    </div>
                    <div class="d-flex-inline align-items-baseline c-exam-list__total">
                        <div class="c-exam-list__currency">NGN</div>
                        <span class="c-exam-list__amount"></span>
                    </div>
                </div>
            </div>
            <div data-section-name="personal-info" class="c-wizard-form__section">
                <p class="c-wizard-form__info">
                    Fill in your personal information to let us know who you are
                </p>
                <div class="form-row">
                    <div class="form-group col-md-12" data-parsley-radio-required="rbPersonalTitle"><!--data-parsley-min-purchase    data-parsley-radio-required-->
                        <!-- <legend class="col-form-label">Title</legend> -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="rbPersonalTitleMr" name="rbPersonalTitle" value="Mr">
                            <label for="rbTitleMr" class="form-check-label">Mr</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="rbPersonalTitleMrs" name="rbPersonalTitle" value="Mrs">
                            <label for="rbTitleMrs" class="form-check-label">Mrs</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="rbPersonalTitleMs" name="rbPersonalTitle" value="Ms">
                            <label for="rbTitleMs" class="form-check-label">Ms</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="rbPersonalTitleMiss" name="rbPersonalTitle" value="Miss">
                            <label for="rbTitleMiss" class="form-check-label">Miss</label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="txtLastName">Last Name</label>
                        <input type="text" id="txtPersonalLastName" name="txtPersonalLastName" class="form-control form-control-sm" placeholder="Ibrahim" required="">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtFirstName">First Name</label>
                        <input type="text" id="txtPersonalFirstName" name="txtPersonalFirstName" class="form-control form-control-sm" placeholder="Issa" required="">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtOtherName">Other Names</label>
                        <input type="text" id="txtPersonalOtherName" name="txtPersonalOtherName" class="form-control form-control-sm" placeholder="Abdullahi">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4" data-parsley-radio-required="rbPersonalGender">
                        <legend class="col-form-label">Gender</legend>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="rbPersonalGender" id="rbPersonalGenderMale" value="1">
                            <label for="rbPersonalGenderMale" class="form-check-label">Male</label>
                        </div>       
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="rbPersonalGender" id="rbPersonalGenderFemale" value="1">
                            <label for="rbPersonalGenderFemale" class="form-check-label">Female</label>
                        </div>                     
                    </div>
                    <div class="form-group col-md-4">
                        <label for="dtpPersonalDob">Birth Date</label>
                        <input type="text" class="form-control form-control-sm date-control" id="dtpPersonalDob" name="dtpPersonalDob" placeholder="DD/MM/YYYY" required data-parsley-dateformat>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtPersonalFirstLanguage">First Language</label>
                        <input type="text" id="txtPersonalFirstLanguage" name="txtPersonalFirstLanguage" class="form-control form-control-sm" placeholder="Igbo" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="ddlPersonalCountry">Country</label>
                        <select class="custom-select custom-select-sm" name="ddlPersonalCountry" id="ddlPersonalCountry" required>
                            <option selected="selected" disabled="disabled" value="">Select Country</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="ddlPersonalState">State</label>
                        <select class="custom-select custom-select-sm" name="ddlPersonalState" id="ddlPersonalState" required>
                            <option selected="selected" disabled="disabled" value="">Select State</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtPersonalPhoneNumber">Phone Number</label>
                        <input type="text" class="form-control form-control-sm" id="txtPersonalPhoneNumber" name="txtPersonalPhoneNumber" placeholder="08064832932" required data-parsley-type="digits">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="txtPersonalPassportNumber">Passport No.</label>
                        <input type="text" class="form-control form-control-sm" id="txtPersonalPassportNumber" name="txtPersonalPassportNumber" placeholder="" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="dtpPersonalExpiryDate">Expiry Date</label>
                        <input type="text" class="form-control form-control-sm date-control" id="dtpPersonalExpiryDate" name="dtpPersonalExpiryDate" placeholder="DD/MM/YYYY" required data-parsley-dateformat>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="txtPersonalPermanentAddress">Permanent Address</label>
                        <input type="text" class="form-control form-control-sm" id="txtPersonalPermanentAddress" name="txtPersonalPermanentAddress" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="txtPersonalCurrentLevelOfStudy">Current Level of Study</label>
                        <input type="text" class="form-control form-control-sm" id="txtPersonalCurrentLevelOfStudy" name="txtPersonalCurrentLevelOfStudy" placeholder="BSc" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="txtPersonalNextLevelOfStudy">Next Level of Study</label>
                        <input type="text" class="form-control form-control-sm" id="txtPersonalNextLevelOfStudy" name="txtPersonalNextLevelOfStudy" placeholder="Masters" required>
                    </div>
                </div>
            </div>
            <div data-section-name="guardian-info" class="c-wizard-form__section">
                <p class="c-wizard-form__info">
                    Guardian Information
                </p>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="txtGuardianLastName">Last Name</label>
                        <input type="text" class="form-control form-control-sm" id="txtGuardianLastName" name="txtGuardianLastName" placeholder="Ibrahim" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="txtGuardianFirstName">First Name</label>
                        <input type="text" class="form-control form-control-sm" id="txtGuardianFirstName" name="txtGuardianFirstName" placeholder="Sadiq" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ddlGuardianCountry">Country</label>
                        <select class="custom-select custom-select-sm" id="ddlGuardianCountry" name="ddlGuardianCountry" required>
                            <option selected="selected" disabled="disabled" value="">Select Country</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ddlGuardianState">State</label>
                        <select class="custom-select custom-select-sm" name="ddlGuardianState" id="ddlGuardianState" required>
                            <option selected="selected" disabled="disabled" value="">Select State</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="txtGuardianEducationalBackground">Educational Background</label>
                        <input type="text" class="form-control form-control-sm" id="txtGuardianEducationalBackground" name="txtGuardianEducationalBackground" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtGuardianOccupation">Occupation</label>
                        <input type="text" class="form-control form-control-sm" id="txtGuardianOccupation" name="txtGuardianOccupation" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtGuardianCurrentPosition">Current Position</label>
                        <input type="text" class="form-control form-control-sm" id="txtGuardianCurrentPosition" name="txtGuardianCurrentPosition" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="txtGuardianOfficeAddress">Office Address</label>
                        <input type="text" class="form-control form-control-sm" id="txtGuardianOfficeAddress" name="txtGuardianOfficeAddress" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="txtGuardianEmailAddress">Email Address</label>
                        <input type="email" class="form-control form-control-sm" id="txtGuardianEmailAddress" name="txtGuardianEmailAddress" placeholder="example@gmail.com" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="txtGuardianPhoneNumber">Phone Number</label>
                        <input type="text" class="form-control form-control-sm" id="txtGuardianPhoneNumber" name="txtGuardianPhoneNumber" placeholder="09048839321" required data-parsley-type="number">
                    </div>
                </div>
            </div>
            <div data-section-name="done" class="c-wizard-form__section">
                <p class="c-wizard-form__info">
                    Thank you for completing our application form and purchasing our products and services.
                </p>
            </div>
        </div>
        <div class="c-wizard-form__buttons">
            <button id="btnPrevSection" type="button" class="btn btn-secondary c-wizard-form__button c-wizard-form__button--back">
                <span class="">Back</span>
            </button>                
            <div class="c-wizard-form__button--group">
                <button id="btnNextSection" type="button" class="btn btn-secondary c-wizard-form__button c-wizard-form__button--next">
                    <span class="">Next</span>
                </button>
                <button id="btnSubmitApplication" type="button" class="btn btn-secondary c-wizard-form__button c-wizard-form__button--submit d-none">
                    <span class="">Submit</span>
                </button>
            </div>
        </div>
    </form>
</div>
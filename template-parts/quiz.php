<?php
/**
 * The homepage "Find Your Best Setup Path" quiz.
 *
 * Markup only; behaviour lives in assets/js/quiz.js and every price it shows
 * comes from cpfConfig.packages, which is built from the Packages CPT.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="hero-quiz-panel quiz-card" id="hero-quiz">
<div class="hero-quiz-headrow"><span aria-hidden="true" class="hero-quiz-headtick" id="hero-quiz-headtick">✓</span><h3 id="hero-quiz-heading">Find Your Best Setup Path</h3></div>
<p class="hero-quiz-sub" id="hero-quiz-subhead">Answer a few quick questions in about 30 seconds</p>
<div class="hero-quiz-progress">
<div class="hero-quiz-progress-label">
<span id="hero-quiz-step-label">Step 1 of 3</span>
<span id="hero-quiz-pct">33%</span>
</div>
<div class="hero-quiz-progress-bar">
<div class="hero-quiz-progress-fill" id="hero-quiz-bar" style="width:33%"></div>
</div>
</div>
<div class="hero-quiz-step active" data-step-id="country">
<p>Where do you live?</p>
<select aria-label="Select your country" class="hero-quiz-select" id="hero-quiz-country">
<option value="">Select your country…</option>
<optgroup label="Most common">
<option>Bahrain</option>
<option>Bangladesh</option>
<option>Egypt</option>
<option>India</option>
<option>Kuwait</option>
<option>Nigeria</option>
<option>Oman</option>
<option>Pakistan</option>
<option>Philippines</option>
<option>Qatar</option>
<option>Saudi Arabia</option>
<option>Turkey</option>
<option>United Arab Emirates</option>
<option>United Kingdom</option>
</optgroup>
<optgroup label="All countries (A–Z)">
<option>Afghanistan</option>
<option>Albania</option>
<option>Algeria</option>
<option>Andorra</option>
<option>Angola</option>
<option>Antigua and Barbuda</option>
<option>Argentina</option>
<option>Armenia</option>
<option>Australia</option>
<option>Austria</option>
<option>Azerbaijan</option>
<option>Bahamas</option>
<option>Bahrain</option>
<option>Bangladesh</option>
<option>Barbados</option>
<option>Belarus</option>
<option>Belgium</option>
<option>Belize</option>
<option>Benin</option>
<option>Bhutan</option>
<option>Bolivia</option>
<option>Bosnia and Herzegovina</option>
<option>Botswana</option>
<option>Brazil</option>
<option>Brunei</option>
<option>Bulgaria</option>
<option>Burkina Faso</option>
<option>Burundi</option>
<option>Cabo Verde</option>
<option>Cambodia</option>
<option>Cameroon</option>
<option>Canada</option>
<option>Central African Republic</option>
<option>Chad</option>
<option>Chile</option>
<option>China</option>
<option>Colombia</option>
<option>Comoros</option>
<option>Congo (DRC)</option>
<option>Congo (Republic)</option>
<option>Costa Rica</option>
<option>Croatia</option>
<option>Cuba</option>
<option>Cyprus</option>
<option>Czechia</option>
<option>Denmark</option>
<option>Djibouti</option>
<option>Dominica</option>
<option>Dominican Republic</option>
<option>Ecuador</option>
<option>Egypt</option>
<option>El Salvador</option>
<option>Equatorial Guinea</option>
<option>Eritrea</option>
<option>Estonia</option>
<option>Eswatini</option>
<option>Ethiopia</option>
<option>Fiji</option>
<option>Finland</option>
<option>France</option>
<option>Gabon</option>
<option>Gambia</option>
<option>Georgia</option>
<option>Germany</option>
<option>Ghana</option>
<option>Greece</option>
<option>Grenada</option>
<option>Guatemala</option>
<option>Guinea</option>
<option>Guinea-Bissau</option>
<option>Guyana</option>
<option>Haiti</option>
<option>Honduras</option>
<option>Hong Kong</option>
<option>Hungary</option>
<option>Iceland</option>
<option>India</option>
<option>Indonesia</option>
<option>Iran</option>
<option>Iraq</option>
<option>Ireland</option>
<option>Israel</option>
<option>Italy</option>
<option>Ivory Coast</option>
<option>Jamaica</option>
<option>Japan</option>
<option>Jordan</option>
<option>Kazakhstan</option>
<option>Kenya</option>
<option>Kiribati</option>
<option>Kosovo</option>
<option>Kuwait</option>
<option>Kyrgyzstan</option>
<option>Laos</option>
<option>Latvia</option>
<option>Lebanon</option>
<option>Lesotho</option>
<option>Liberia</option>
<option>Libya</option>
<option>Liechtenstein</option>
<option>Lithuania</option>
<option>Luxembourg</option>
<option>Macao</option>
<option>Madagascar</option>
<option>Malawi</option>
<option>Malaysia</option>
<option>Maldives</option>
<option>Mali</option>
<option>Malta</option>
<option>Marshall Islands</option>
<option>Mauritania</option>
<option>Mauritius</option>
<option>Mexico</option>
<option>Micronesia</option>
<option>Moldova</option>
<option>Monaco</option>
<option>Mongolia</option>
<option>Montenegro</option>
<option>Morocco</option>
<option>Mozambique</option>
<option>Myanmar</option>
<option>Namibia</option>
<option>Nauru</option>
<option>Nepal</option>
<option>Netherlands</option>
<option>New Zealand</option>
<option>Nicaragua</option>
<option>Niger</option>
<option>Nigeria</option>
<option>North Korea</option>
<option>North Macedonia</option>
<option>Norway</option>
<option>Oman</option>
<option>Pakistan</option>
<option>Palau</option>
<option>Palestine</option>
<option>Panama</option>
<option>Papua New Guinea</option>
<option>Paraguay</option>
<option>Peru</option>
<option>Philippines</option>
<option>Poland</option>
<option>Portugal</option>
<option>Qatar</option>
<option>Romania</option>
<option>Russia</option>
<option>Rwanda</option>
<option>Saint Kitts and Nevis</option>
<option>Saint Lucia</option>
<option>Saint Vincent and the Grenadines</option>
<option>Samoa</option>
<option>San Marino</option>
<option>Sao Tome and Principe</option>
<option>Saudi Arabia</option>
<option>Senegal</option>
<option>Serbia</option>
<option>Seychelles</option>
<option>Sierra Leone</option>
<option>Singapore</option>
<option>Slovakia</option>
<option>Slovenia</option>
<option>Solomon Islands</option>
<option>Somalia</option>
<option>South Africa</option>
<option>South Korea</option>
<option>South Sudan</option>
<option>Spain</option>
<option>Sri Lanka</option>
<option>Sudan</option>
<option>Suriname</option>
<option>Sweden</option>
<option>Switzerland</option>
<option>Syria</option>
<option>Taiwan</option>
<option>Tajikistan</option>
<option>Tanzania</option>
<option>Thailand</option>
<option>Timor-Leste</option>
<option>Togo</option>
<option>Tonga</option>
<option>Trinidad and Tobago</option>
<option>Tunisia</option>
<option>Turkey</option>
<option>Turkmenistan</option>
<option>Tuvalu</option>
<option>Uganda</option>
<option>Ukraine</option>
<option>United Arab Emirates</option>
<option>United Kingdom</option>
<option>United States</option>
<option>Uruguay</option>
<option>Uzbekistan</option>
<option>Vanuatu</option>
<option>Vatican City</option>
<option>Venezuela</option>
<option>Vietnam</option>
<option>Yemen</option>
<option>Zambia</option>
<option>Zimbabwe</option>
</optgroup>
</select>
</div>
<div class="hero-quiz-step" data-step-id="destination">
<p>Where do you want to set up your company?</p>
<button class="hero-quiz-option" data-field="destination" data-value="usa" onclick="heroQuizPick('destination','usa',this)" type="button"><input aria-hidden="true" name="destination" tabindex="-1" type="radio"/> U.S.</button>
<button class="hero-quiz-option" data-field="destination" data-value="canada" onclick="heroQuizPick('destination','canada',this)" type="button"><input aria-hidden="true" name="destination" tabindex="-1" type="radio"/> Canada</button>
<button class="hero-quiz-option" data-field="destination" data-value="both" onclick="heroQuizPick('destination','both',this)" type="button"><input aria-hidden="true" name="destination" tabindex="-1" type="radio"/> Both U.S. and Canada</button>
<button class="hero-quiz-option" data-field="destination" data-value="help_decide" onclick="heroQuizPick('destination','help_decide',this)" type="button"><input aria-hidden="true" name="destination" tabindex="-1" type="radio"/> Help me decide</button>
</div>
<div class="hero-quiz-step" data-step-id="goal">
<p>What is your main business goal?</p>
<button class="hero-quiz-option" data-field="business_goal" data-value="online_business" onclick="heroQuizPick('business_goal','online_business',this)" type="button"><input aria-hidden="true" name="business_goal" tabindex="-1" type="radio"/> Online business / freelancing</button>
<button class="hero-quiz-option" data-field="business_goal" data-value="ecommerce" onclick="heroQuizPick('business_goal','ecommerce',this)" type="button"><input aria-hidden="true" name="business_goal" tabindex="-1" type="radio"/> E-commerce / Amazon / Shopify</button>
<button class="hero-quiz-option" data-field="business_goal" data-value="consulting" onclick="heroQuizPick('business_goal','consulting',this)" type="button"><input aria-hidden="true" name="business_goal" tabindex="-1" type="radio"/> Consulting or agency</button>
<button class="hero-quiz-option" data-field="business_goal" data-value="international_trading" onclick="heroQuizPick('business_goal','international_trading',this)" type="button"><input aria-hidden="true" name="business_goal" tabindex="-1" type="radio"/> International trading</button>
<button class="hero-quiz-option" data-field="business_goal" data-value="not_sure" onclick="heroQuizPick('business_goal','not_sure',this)" type="button"><input aria-hidden="true" name="business_goal" tabindex="-1" type="radio"/> I am not sure yet</button>
</div>
<div class="hero-quiz-step" data-step-id="contact">
<p>Almost done — get your personalized path</p>
<input autocomplete="name" class="hero-quiz-input" id="hero-quiz-name" placeholder="Full name" type="text"/>
<input autocomplete="tel" class="hero-quiz-input" id="hero-quiz-phone" placeholder="WhatsApp number with country code" type="tel"/>
<input autocomplete="email" class="hero-quiz-input" id="hero-quiz-email" placeholder="Email address (optional)" type="email"/>
<input aria-hidden="true" autocomplete="off" id="hero-quiz-gotcha" style="position:absolute;left:-9999px" tabindex="-1" type="text"/>
<p style="font-size:.72rem;color:var(--muted);margin:2px 0 0">We use this only to contact you about your setup request.</p>
</div>
<div class="hero-quiz-thanks" id="hero-quiz-thanks">
<div class="hero-quiz-result-package" id="hero-quiz-result-package">
<span class="hero-quiz-result-tag" id="hero-quiz-result-tag">Recommended</span>
<span class="hero-quiz-result-name" id="hero-quiz-result-name">United States Company Setup</span>
<span class="hero-quiz-result-price" id="hero-quiz-result-price"><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'starter' )->ID ) ); ?></span>
<p class="hero-quiz-result-detail" id="hero-quiz-result-detail">USD · + state fees · LLC or C-Corp setup for non-residents.</p>
</div>
<div class="hero-quiz-result-actions">
<a class="btn btn-gold" href="/start/" id="hero-quiz-checkout">Start This Package</a>
<a class="btn btn-outline" href="<?php echo esc_url( cpf_get_setting( 'calendly_url' ) ); ?>" id="hero-quiz-book-call">Book a Free 15-Min Call</a>
<a class="btn btn-outline hero-quiz-wa" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I completed the setup quiz and I want help with my recommended package.' ) ); ?>" id="hero-quiz-whatsapp"><i class="fa-brands fa-whatsapp"></i> WhatsApp Advisor</a>
</div>
<button class="hero-quiz-reset-link" onclick="resetHeroQuiz()" type="button">Start over</button>
</div>
<div class="hero-quiz-actions" id="hero-quiz-actions">
<button class="hero-quiz-back hidden" id="hero-quiz-back" onclick="heroQuizBack()" type="button">Back</button>
<button class="btn btn-gold hero-quiz-next" disabled="" id="hero-quiz-next" onclick="heroQuizNext()" type="button">Continue</button>
</div>
<div class="hero-quiz-reassure" id="hero-quiz-reassure">
<span><i class="fa-solid fa-check"></i> No credit card required</span>
<span><i class="fa-solid fa-clock"></i> 30-second setup check</span>
<span><i class="fa-solid fa-lock"></i> Your information is secure</span>
</div>
</div>

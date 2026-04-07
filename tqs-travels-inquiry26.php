<?php
/**
 * Plugin Name: TQS Travels - Travel Inquiry Form
 * Plugin URI:  https://tqstravels.com
 * Description: Travel inquiry form for TQS Travels. One Way, Return and Multi-City with passenger rules.
 * Version:     1.3.0
 * Author:      TQS Travels
 * License:     GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TQS_INQUIRY_DEFAULT_EMAIL', 'travel@travelbytqs.com' );
// ============================================================
// ENQUEUE INLINE CSS + JS
// ============================================================
add_action( 'wp_enqueue_scripts', 'tqs_enqueue_assets' );
function tqs_enqueue_assets() {
    add_action( 'wp_head',   'tqs_output_css' );
    add_action( 'wp_footer', 'tqs_output_js'  );
}

// ============================================================
// INLINE CSS
// ============================================================
function tqs_output_css() { ?>
<style id="tqs-form-styles">
@import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Nunito:wght@400;500;600;700&display=swap');
:root{
  --bg-dark:#1a0a2e;--bg-card:#23103d;--bg-input:#2d1654;
  --primary:#c724b1;--primary-dark:#9b1a8a;--primary-glow:rgba(199,36,177,0.25);
  --accent:#e8b4f8;--accent-light:#f5d6ff;
  --text:#f0e0ff;--text-muted:#a87bc0;--border:#5a2d7a;
  --error:#ff4d6d;--success:#39d98a;
}
.tqs-form-wrapper{max-width:820px;margin:40px auto;font-family:'Nunito','Segoe UI',sans-serif;color:var(--text);}
.tqs-form-header{background:linear-gradient(135deg,#1a0a2e 0%,#3b1060 50%,#1a0a2e 100%);border:1px solid var(--primary);box-shadow:0 0 24px var(--primary-glow),inset 0 0 40px rgba(199,36,177,.08);padding:32px;border-radius:12px 12px 0 0;text-align:center;}
.tqs-form-header h2{font-family:'Rajdhani',sans-serif;font-size:1.9em;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent-light);text-shadow:0 0 18px var(--primary),0 0 40px rgba(199,36,177,.5);margin:0 0 8px;}
.tqs-form-header p{color:var(--text-muted);font-size:.97em;margin:0;}
.tqs-inquiry-form{background:var(--bg-card);border:1px solid var(--border);border-top:none;border-radius:0 0 12px 12px;padding:32px;box-shadow:0 8px 32px rgba(0,0,0,.5);}
.tqs-form-section{margin-bottom:30px;padding-bottom:24px;border-bottom:1px solid rgba(90,45,122,.5);}
.tqs-form-section:last-of-type{border-bottom:none;}
.tqs-form-section h3{font-family:'Rajdhani',sans-serif;font-size:1.05em;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);margin:0 0 18px;padding-bottom:8px;border-bottom:2px solid var(--primary);text-shadow:0 0 10px var(--primary-glow);}
.tqs-hint{color:var(--text-muted);font-size:.88em;margin:-10px 0 16px;font-style:italic;}
.tqs-field label{font-weight:700;font-size:.82em;color:var(--accent);margin-bottom:6px;display:block;text-transform:uppercase;letter-spacing:.07em;}
.tqs-row{display:flex;gap:18px;flex-wrap:wrap;margin-bottom:14px;}
.tqs-field{flex:1;min-width:200px;display:flex;flex-direction:column;margin-bottom:10px;}
.tqs-field input[type=text],.tqs-field input[type=email],.tqs-field input[type=tel],.tqs-field input[type=date],.tqs-field input[type=number],.tqs-field select:not(.tqs-airport-select):not(.tqs-dial-select),.tqs-field textarea,.tqs-phone-number,.tqs-comments-box{padding:10px 14px;background:var(--bg-input);border:1px solid var(--border);border-radius:7px;color:var(--text);font-size:.97em;font-family:'Nunito',sans-serif;width:100%;box-sizing:border-box;transition:border-color .2s,box-shadow .2s,background .2s;}
.tqs-field input::placeholder,.tqs-phone-number::placeholder,.tqs-comments-box::placeholder{color:var(--text-muted);font-style:italic;}
.tqs-field input:focus,.tqs-field select:focus,.tqs-field textarea:focus,.tqs-phone-number:focus,.tqs-comments-box:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-glow);background:#3a1a60;}
.tqs-field input[type=date]::-webkit-calendar-picker-indicator{filter:invert(.8) sepia(1) saturate(3) hue-rotate(260deg);cursor:pointer;}
.required{color:var(--primary);}
.tqs-optional-tag{background:rgba(199,36,177,.15);color:var(--accent);font-size:.7em;font-weight:700;padding:2px 9px;border-radius:10px;letter-spacing:.05em;text-transform:uppercase;vertical-align:middle;margin-left:6px;border:1px solid var(--border);}
.tqs-field-hint{display:block;font-size:.77em;color:var(--text-muted);margin-top:4px;font-style:italic;}
/* Travel type cards */
.tqs-travel-type-selector{display:flex;gap:14px;flex-wrap:wrap;}
.tqs-type-card{flex:1;min-width:120px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:18px 12px;background:var(--bg-dark);border:2px solid var(--border);border-radius:10px;cursor:pointer;user-select:none;transition:all .15s ease;}
.tqs-type-card input[type=radio]{display:none;}
.tqs-type-label{font-family:'Rajdhani',sans-serif;font-weight:700;font-size:1em;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);text-align:center;}
.tqs-type-card:hover{border-color:var(--accent);background:rgba(199,36,177,.08);}
.tqs-type-card:active{transform:scale(.96);background:rgba(199,36,177,.18);border-color:var(--primary);}
.tqs-type-card.active{border-color:var(--primary);background:linear-gradient(135deg,rgba(199,36,177,.18),rgba(90,45,122,.35));box-shadow:0 0 16px var(--primary-glow),inset 0 0 20px rgba(199,36,177,.1);}
.tqs-type-card.active .tqs-type-label{color:var(--accent-light);text-shadow:0 0 10px var(--primary);}
.tqs-type-section{display:none;}
/* Airport field */
.tqs-airport-wrap{display:flex;flex-direction:column;gap:0;width:100%;}
.tqs-airport-search{padding:9px 13px;background:#3a1a60;border:1px solid var(--border);border-bottom:none;border-radius:7px 7px 0 0;color:var(--text);font-size:.9em;font-family:'Nunito',sans-serif;width:100%;box-sizing:border-box;transition:border-color .2s,box-shadow .2s;}
.tqs-airport-search::placeholder{color:var(--text-muted);font-style:italic;}
.tqs-airport-search:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-glow);}
.tqs-airport-select{padding:10px 13px;background:var(--bg-input);border:1px solid var(--border);border-radius:0 0 7px 7px;color:var(--text);font-size:.95em;font-family:'Nunito',sans-serif;width:100%;box-sizing:border-box;transition:border-color .2s,box-shadow .2s;cursor:pointer;}
.tqs-airport-select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-glow);background:#3a1a60;}
.tqs-airport-select option,.tqs-airport-select optgroup{background:#23103d;color:var(--text);}
.tqs-airport-other{margin-top:7px;padding:10px 13px;background:var(--bg-input);border:2px dashed var(--primary);border-radius:7px;color:var(--text);font-size:.95em;font-family:'Nunito',sans-serif;width:100%;box-sizing:border-box;}
.tqs-airport-other::placeholder{color:var(--text-muted);font-style:italic;}
.tqs-airport-other:focus{outline:none;border-color:var(--accent-light);box-shadow:0 0 0 3px var(--primary-glow);background:#3a1a60;}
/* Phone */
.tqs-phone-wrap{display:flex;align-items:center;gap:8px;flex-wrap:nowrap;width:100%;}
.tqs-dial-select{flex:0 0 auto;width:185px;padding:10px;background:var(--bg-input);border:1px solid var(--border);border-radius:7px;color:var(--text);font-size:.88em;font-family:'Nunito',sans-serif;cursor:pointer;box-sizing:border-box;transition:border-color .2s,box-shadow .2s;}
.tqs-dial-select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-glow);background:#3a1a60;}
.tqs-dial-select option{background:#23103d;color:var(--text);}
.tqs-dial-other{flex:0 0 auto;width:72px;padding:10px 8px;background:var(--bg-input);border:2px dashed var(--primary);border-radius:7px;color:var(--text);font-size:.92em;text-align:center;box-sizing:border-box;}
.tqs-dial-other:focus{outline:none;border-color:var(--accent-light);box-shadow:0 0 0 3px var(--primary-glow);}
.tqs-phone-number{flex:1 1 auto;min-width:0;padding:10px 13px;background:var(--bg-input);border:1px solid var(--border);border-radius:7px;color:var(--text);font-size:.97em;font-family:'Nunito',sans-serif;box-sizing:border-box;}
.tqs-phone-number::placeholder{color:var(--text-muted);font-style:italic;}
.tqs-phone-number:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-glow);background:#3a1a60;}
.tqs-phone-error{background:rgba(255,77,109,.12);border:1px solid var(--error);border-left:4px solid var(--error);color:#ff8fa3;padding:8px 14px;border-radius:6px;margin-top:6px;font-size:.87em;font-weight:600;}
.tqs-wa-same-label{display:flex;align-items:center;gap:8px;font-size:.93em;color:var(--text-muted);cursor:pointer;font-weight:600!important;text-transform:none!important;letter-spacing:normal!important;margin-bottom:4px;}
.tqs-wa-same-label input[type=checkbox]{accent-color:var(--primary);width:16px;height:16px;cursor:pointer;}
/* Passengers */
.tqs-pax-grid{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:16px;}
.tqs-pax-card{flex:1;min-width:170px;display:flex;align-items:center;gap:14px;background:var(--bg-dark);border:2px solid var(--border);border-radius:10px;padding:14px 16px;transition:all .2s ease;}
.tqs-pax-card.pax-active{border-color:var(--primary);background:rgba(199,36,177,.1);box-shadow:0 0 14px var(--primary-glow);}
.tqs-pax-info{flex:1;}
.tqs-pax-type{font-family:'Rajdhani',sans-serif;font-weight:700;font-size:1em;letter-spacing:.06em;text-transform:uppercase;color:var(--accent);}
.tqs-pax-age{font-size:.78em;color:var(--text-muted);margin-top:2px;}
.tqs-pax-counter{display:flex;align-items:center;gap:10px;flex-shrink:0;}
.tqs-pax-btn{width:34px;height:34px;border-radius:50%;border:2px solid var(--primary);background:transparent;color:var(--accent);font-size:1.3em;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s ease;padding:0;line-height:1;}
.tqs-pax-btn:hover:not(:disabled){background:var(--primary);color:#fff;box-shadow:0 0 12px var(--primary-glow);}
.tqs-pax-btn:active:not(:disabled){background:var(--primary-dark);transform:scale(.9);box-shadow:0 0 18px var(--primary-glow);}
.tqs-pax-btn:focus:not(:disabled){outline:none;box-shadow:0 0 0 3px var(--primary-glow);}
.tqs-pax-btn:disabled{opacity:.2;cursor:not-allowed;border-color:var(--border);}
.tqs-pax-count{font-family:'Rajdhani',sans-serif;font-size:1.5em;font-weight:700;color:var(--accent-light);min-width:28px;text-align:center;text-shadow:0 0 10px var(--primary-glow);}
.tqs-pax-summary{background:rgba(90,45,122,.3);border:1px solid var(--border);border-radius:8px;padding:11px 18px;font-size:.9em;color:var(--accent);margin-top:4px;}
.tqs-pax-summary strong{color:var(--accent-light);}
.tqs-pax-summary.pax-warn{background:rgba(255,160,0,.12);border-color:#ffb300;color:#ffd54f;}
.tqs-pax-summary.pax-full{background:rgba(255,77,109,.12);border-color:var(--error);color:#ff8fa3;}
.tqs-pax-error{background:rgba(255,77,109,.12);border:1px solid var(--error);border-left:5px solid var(--error);color:#ff8fa3;padding:10px 16px;border-radius:6px;margin-top:10px;font-size:.93em;font-weight:600;}
/* Multi-city */
.tqs-mc-leg{background:rgba(26,10,46,.7);border:1px solid var(--border);border-radius:9px;padding:16px;margin-bottom:14px;}
.tqs-mc-leg-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.tqs-leg-badge{background:var(--primary);color:#fff;padding:4px 16px;border-radius:20px;font-family:'Rajdhani',sans-serif;font-size:.88em;font-weight:700;letter-spacing:.07em;text-transform:uppercase;box-shadow:0 0 10px var(--primary-glow);}
.tqs-remove-leg{background:transparent;color:var(--error);border:1px solid var(--error);padding:4px 14px;border-radius:6px;cursor:pointer;font-size:.83em;font-weight:700;transition:all .15s ease;}
.tqs-remove-leg:hover{background:var(--error);color:#fff;box-shadow:0 0 10px rgba(255,77,109,.35);}
.tqs-remove-leg:active,.tqs-remove-leg:focus{background:#cc1a35;color:#fff;box-shadow:0 0 0 3px rgba(255,77,109,.3);outline:none;transform:scale(.95);}
.tqs-mc-dates-row{background:rgba(90,45,122,.2);border:1px dashed var(--border);border-radius:8px;padding:12px 14px 4px;margin-top:6px;}
.tqs-field--date{max-width:220px;}
.tqs-leg-date-error{color:#ff8fa3;font-size:.85em;font-weight:600;align-self:center;padding-top:18px;}
.tqs-add-leg-btn{background:transparent;border:2px dashed var(--primary);color:var(--accent);padding:11px 22px;border-radius:8px;font-size:.95em;font-family:'Rajdhani',sans-serif;font-weight:700;letter-spacing:.07em;text-transform:uppercase;cursor:pointer;width:100%;margin-top:4px;transition:all .15s ease;}
.tqs-add-leg-btn:hover{background:rgba(199,36,177,.12);color:var(--accent-light);border-color:var(--accent);box-shadow:0 0 12px var(--primary-glow);}
.tqs-add-leg-btn:active,.tqs-add-leg-btn:focus{background:rgba(199,36,177,.25);border-style:solid;border-color:var(--primary);color:#fff;box-shadow:0 0 0 3px var(--primary-glow);outline:none;}
/* Checkboxes */
.tqs-checkbox-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-top:8px;}
.tqs-checkbox-label{display:flex;align-items:center;gap:9px;font-size:.93em;color:var(--text-muted);cursor:pointer;font-weight:500!important;text-transform:none!important;letter-spacing:normal!important;transition:color .15s;padding:4px 0;}
.tqs-checkbox-label input[type=checkbox]{accent-color:var(--primary);width:16px;height:16px;cursor:pointer;flex-shrink:0;}
.tqs-checkbox-label:hover{color:var(--accent);}
.tqs-checkbox-label:has(input:checked){color:var(--accent-light);font-weight:700!important;}
/* Return date error */
.tqs-return-date-error{display:flex;align-items:center;gap:6px;background:rgba(255,77,109,.12);border:1px solid var(--error);border-left:5px solid var(--error);color:#ff8fa3;padding:10px 16px;border-radius:6px;margin-top:6px;font-size:.9em;font-weight:600;}
/* Comments */
.tqs-comments-grid{display:flex;flex-direction:column;gap:20px;}
.tqs-comments-box{background:var(--bg-input);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:'Nunito',sans-serif;font-size:.97em;padding:12px 14px;width:100%;box-sizing:border-box;resize:vertical;min-height:130px;line-height:1.6;}
.tqs-comments-box:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-glow);background:#3a1a60;}
.tqs-char-counter{text-align:right;font-size:.79em;color:var(--text-muted);margin-top:4px;}
.tqs-char-counter.warn{color:#ffb300;font-weight:700;}
.tqs-char-counter.over{color:var(--error);font-weight:700;}
/* Submit */
.tqs-submit-row{text-align:center;margin-top:14px;}
.tqs-submit-btn{background:linear-gradient(135deg,var(--primary-dark),var(--primary));color:#fff;border:none;padding:15px 52px;font-family:'Rajdhani',sans-serif;font-size:1.1em;font-weight:700;letter-spacing:.12em;text-transform:uppercase;border-radius:9px;cursor:pointer;box-shadow:0 0 22px var(--primary-glow);transition:all .15s ease;}
.tqs-submit-btn:hover{background:linear-gradient(135deg,var(--primary),#e040d0);box-shadow:0 0 32px rgba(199,36,177,.5);transform:translateY(-2px);}
.tqs-submit-btn:active,.tqs-submit-btn:focus{background:linear-gradient(135deg,#7a1570,var(--primary-dark));box-shadow:0 0 0 4px var(--primary-glow),0 0 20px var(--primary-glow);transform:translateY(0);outline:none;}
/* Messages */
.tqs-success{background:rgba(57,217,138,.1);border:1px solid var(--success);border-left:5px solid var(--success);padding:22px 26px;border-radius:10px;color:#a8f0cb;max-width:820px;margin:30px auto;font-family:'Nunito',sans-serif;}
.tqs-success h3{font-family:'Rajdhani',sans-serif;letter-spacing:.08em;font-size:1.2em;margin:0 0 8px;color:var(--success);text-transform:uppercase;}
.tqs-errors{background:rgba(255,77,109,.1);border:1px solid var(--error);border-left:5px solid var(--error);padding:14px 20px;border-radius:8px;color:#ff8fa3;margin-bottom:20px;font-family:'Nunito',sans-serif;}
.tqs-errors ul{margin:0;padding-left:18px;}
.tqs-errors li{margin:5px 0;}
/* Responsive */
@media(max-width:600px){
  .tqs-inquiry-form{padding:18px;}
  .tqs-row{flex-direction:column;gap:0;}
  .tqs-field--date{max-width:100%;}
  .tqs-travel-type-selector{flex-direction:column;}
  .tqs-pax-grid{flex-direction:column;}
  .tqs-phone-wrap{flex-wrap:wrap;}
  .tqs-dial-select{width:100%;}
  .tqs-phone-number{width:100%;}
}
/* Selects global */
.tqs-field select:focus{border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-glow);outline:none;background:#3a1a60;}
</style>
<?php }

// ============================================================
// INLINE JS
// ============================================================
function tqs_output_js() { ?>
<script id="tqs-form-script">
(function(){
'use strict';
var MAX_TOTAL=9,MIN_LEGS=3,legCount=0;
var pax={adults:1,kids:0,infants:0};

/* ---- PASSENGERS ---- */
function initPaxCounts(){
  ['adults','kids','infants'].forEach(function(t){
    var i=document.getElementById('tqs_'+t);
    if(i) pax[t]=parseInt(i.value,10)||0;
  });
  renderPax();
}
window.tqsChangePax=function(type,delta){
  var nv=pax[type]+delta,nt=(pax.adults+pax.kids+pax.infants)-pax[type]+nv;
  clearPaxError();
  if(nv<0)return;
  if(type==='adults'&&nv<1){showPaxError('At least 1 adult is required.');return;}
  if(nt>MAX_TOTAL){showPaxError('Maximum '+MAX_TOTAL+' passengers. Total would be '+nt+'.');return;}
  var na=type==='adults'?nv:pax.adults,ni=type==='infants'?nv:pax.infants;
  if(ni>na){showPaxError('Infants ('+ni+') cannot exceed adults ('+na+').');return;}
  if(type==='adults'&&pax.infants>nv){showPaxError('Cannot reduce adults below infant count ('+pax.infants+'). Reduce infants first.');return;}
  pax[type]=nv;renderPax();
};
function renderPax(){
  var cm={adults:'adult',kids:'kids',infants:'infants'};
  ['adults','kids','infants'].forEach(function(t){
    var d=document.getElementById('pax-count-'+t),inp=document.getElementById('tqs_'+t),card=document.getElementById('pax-card-'+cm[t]);
    if(d)d.textContent=pax[t];if(inp)inp.value=pax[t];
    if(card)card.classList.toggle('pax-active',pax[t]>0);
    var mb=card?card.querySelector('.tqs-pax-minus'):null;
    if(mb)mb.disabled=pax[t]<=(t==='adults'?1:0);
    var pb=card?card.querySelector('.tqs-pax-plus'):null;
    if(pb){var tot=pax.adults+pax.kids+pax.infants;pb.disabled=(tot>=MAX_TOTAL)||(t==='infants'&&pax.infants>=pax.adults);}
  });
  updatePaxSummary();
}
function updatePaxSummary(){
  var tot=pax.adults+pax.kids+pax.infants,bar=document.getElementById('tqs-pax-summary'),txt=document.getElementById('tqs-pax-summary-text');
  if(txt)txt.innerHTML='Total: <strong>'+tot+' / '+MAX_TOTAL+'</strong> passengers &nbsp;&middot;&nbsp; Adults: <strong>'+pax.adults+'</strong> &nbsp;&middot;&nbsp; Children: <strong>'+pax.kids+'</strong> &nbsp;&middot;&nbsp; Infants: <strong>'+pax.infants+'</strong>';
  if(bar){bar.classList.remove('pax-warn','pax-full');if(tot>=MAX_TOTAL)bar.classList.add('pax-full');else if(tot>=7)bar.classList.add('pax-warn');}
}
function showPaxError(m){var e=document.getElementById('tqs-pax-error');if(e){e.textContent=m;e.style.display='block';}}
function clearPaxError(){var e=document.getElementById('tqs-pax-error');if(e){e.textContent='';e.style.display='none';}}

/* ---- TRAVEL TYPE ---- */
function initTravelTypeSwitcher(){
  var radios=document.querySelectorAll('input[name="tqs_travel_type"]');
  if(!radios.length)return;
  radios.forEach(function(r){r.addEventListener('change',function(){switchSection(this.value);updateCardStyles(this);});});
  document.querySelectorAll('.tqs-type-card').forEach(function(c){
    c.addEventListener('click',function(){var r=this.querySelector('input[type="radio"]');if(r){r.checked=true;switchSection(r.value);updateCardStyles(r);}});
  });
  var ch=document.querySelector('input[name="tqs_travel_type"]:checked');
  if(ch)switchSection(ch.value);
}
function switchSection(t){
  document.querySelectorAll('.tqs-type-section').forEach(function(s){s.style.display='none';});
  var tgt=document.getElementById('tqs-section-'+t);if(tgt)tgt.style.display='block';
}
function updateCardStyles(ar){
  document.querySelectorAll('.tqs-type-card').forEach(function(c){c.classList.remove('active');});
  if(ar&&ar.closest('.tqs-type-card'))ar.closest('.tqs-type-card').classList.add('active');
}

/* ---- AIRPORT OTHER ---- */
window.tqsToggleOther=function(sel){
  var wrap=sel.closest('.tqs-airport-wrap'),oi=wrap?wrap.querySelector('.tqs-airport-other'):null;
  if(!oi)return;
  if(sel.value==='other'){oi.style.display='block';oi.setAttribute('required','required');oi.focus();}
  else{oi.style.display='none';oi.removeAttribute('required');oi.value='';}
};

/* ---- AIRPORT SEARCH ---- */
function initSearchOnSelect(sel){
  var wrap=sel.closest('.tqs-airport-wrap');if(!wrap)return;
  var si=wrap.querySelector('.tqs-airport-search');if(!si)return;
  var ogs=Array.from(sel.querySelectorAll('optgroup'));
  si.addEventListener('input',function(){
    var q=this.value.toLowerCase().trim();
    if(!q){ogs.forEach(function(g){g.style.display='';g.querySelectorAll('option').forEach(function(o){o.style.display='';});});return;}
    ogs.forEach(function(g){
      var any=false;
      g.querySelectorAll('option').forEach(function(o){
        if(o.value===''||o.value==='other'||o.textContent.toLowerCase().includes(q)){o.style.display='';if(o.value!==''&&o.value!=='other')any=true;}
        else o.style.display='none';
      });
      g.style.display=any?'':'none';
    });
  });
}
function initAirportSearch(){document.querySelectorAll('.tqs-airport-select').forEach(initSearchOnSelect);}

/* ---- PHONE ---- */
function initPhoneFields(){
  document.querySelectorAll('.tqs-phone-number').forEach(function(inp){
    inp.addEventListener('input',function(){
      var c=this.value.replace(/\D/g,'');if(this.value!==c)this.value=c;
      var eid=this.id==='tqs_phone'?'tqs-phone-error':'tqs-wa-error',ee=document.getElementById(eid);
      if(ee)ee.style.display=(this.value&&!/^\d+$/.test(this.value))?'block':'none';
    });
    inp.addEventListener('keypress',function(e){if(!/[0-9]/.test(e.key)&&!['Backspace','Delete','Tab','Enter','ArrowLeft','ArrowRight'].includes(e.key))e.preventDefault();});
    inp.addEventListener('paste',function(e){e.preventDefault();var p=(e.clipboardData||window.clipboardData).getData('text');this.value=p.replace(/\D/g,'');});
  });
}
function initDialSelects(){
  document.querySelectorAll('.tqs-dial-select').forEach(function(sel){
    sel.addEventListener('change',function(){
      var wrap=this.closest('.tqs-phone-wrap'),oi=wrap?wrap.querySelector('.tqs-dial-other'):null;
      if(!oi)return;
      if(this.value==='other'){oi.style.display='flex';oi.setAttribute('required','required');oi.focus();}
      else{oi.style.display='none';oi.removeAttribute('required');oi.value='';}
    });
  });
}
window.tqsSyncWaCode=function(ps){
  var wc=document.getElementById('tqs_wa_same');
  if(wc&&wc.checked){var ws=document.getElementById('tqs_wa_code');if(ws)ws.value=ps.value;}
};
window.tqsToggleWa=function(cb){
  var wf=document.getElementById('tqs-wa-fields'),we=document.getElementById('tqs-wa-error');
  if(!wf)return;
  if(cb.checked){wf.style.display='none';if(we)we.style.display='none';var wi=document.getElementById('tqs_whatsapp');if(wi){wi.removeAttribute('required');wi.value='';}}
  else wf.style.display='flex';
};
function validatePhoneFields(){
  var blocked=false,pv=document.getElementById('tqs_phone'),pe=document.getElementById('tqs-phone-error');
  if(pv){
    if(!pv.value||!/^\d{4,15}$/.test(pv.value)){if(pe){pe.textContent='Phone number must be digits only (4-15 digits).';pe.style.display='block';}pv.style.borderColor='var(--error)';blocked=true;}
    else{if(pe)pe.style.display='none';pv.style.borderColor='';}
  }
  var wc=document.getElementById('tqs_wa_same'),wv=document.getElementById('tqs_whatsapp'),we=document.getElementById('tqs-wa-error');
  if(wc&&!wc.checked&&wv&&wv.value){
    if(!/^\d{4,15}$/.test(wv.value)){if(we){we.textContent='WhatsApp number must be digits only (4-15 digits).';we.style.display='block';}wv.style.borderColor='var(--error)';blocked=true;}
    else{if(we)we.style.display='none';wv.style.borderColor='';}
  }
  return blocked;
}

/* ---- RETURN DATE ---- */
window.tqsReturnDepChanged=function(di){
  var ai=document.getElementById('tqs_return_date'),ee=document.getElementById('tqs-return-date-error');
  if(!ai)return;ai.min=di.value;
  if(ai.value&&ai.value<=di.value){ai.value='';if(ee){ee.textContent='Return date must be after the departure date.';ee.style.display='flex';}}
  else if(ee)ee.style.display='none';
};
window.tqsReturnArrChanged=function(ai){
  var di=document.getElementById('tqs_travel_date_return'),ee=document.getElementById('tqs-return-date-error');
  if(!di||!ee)return;
  if(di.value&&ai.value&&ai.value<=di.value){ee.textContent='Return date must be after the departure date ('+di.value+').';ee.style.display='flex';ai.style.borderColor='var(--error)';ai.style.boxShadow='0 0 0 3px rgba(255,77,109,.25)';}
  else{ee.style.display='none';ai.style.borderColor='';ai.style.boxShadow='';}
};

/* ---- MULTI-CITY ---- */
function initLegCount(){legCount=document.querySelectorAll('.tqs-mc-leg').length;}
window.tqsValidateLegDates=function(inp){
  var leg=inp.closest('.tqs-mc-leg');if(!leg)return;
  var di=leg.querySelector('.tqs-mc-dep-date'),ai=leg.querySelector('.tqs-mc-arr-date'),ee=leg.querySelector('.tqs-leg-date-error');
  if(!di||!ai||!ee)return;
  if(di.value&&ai.value&&ai.value<di.value){ee.style.display='flex';ai.style.borderColor='var(--error)';ai.style.boxShadow='0 0 0 3px rgba(255,77,109,.25)';}
  else{ee.style.display='none';ai.style.borderColor='';ai.style.boxShadow='';}
  if(di.value)ai.min=di.value;
};
function validateAllLegDates(){
  var err=false;
  document.querySelectorAll('.tqs-mc-leg').forEach(function(leg){
    var di=leg.querySelector('.tqs-mc-dep-date'),ai=leg.querySelector('.tqs-mc-arr-date'),ee=leg.querySelector('.tqs-leg-date-error');
    if(di&&ai&&di.value&&ai.value&&ai.value<di.value){if(ee)ee.style.display='flex';err=true;}
  });
  return err;
}
window.tqsAddLeg=function(){
  legCount++;
  var container=document.getElementById('tqs-mc-legs');if(!container)return;
  var today=new Date().toISOString().split('T')[0];
  var es=document.querySelector('.tqs-mc-leg select.tqs-airport-select');
  var oh=es?es.innerHTML:'<option value="">-- Select Airport --</option>';
  var div=document.createElement('div');div.className='tqs-mc-leg';div.dataset.leg=legCount-1;
  div.innerHTML='<div class="tqs-mc-leg-header"><span class="tqs-leg-badge">Leg '+legCount+'</span><button type="button" class="tqs-remove-leg" onclick="tqsRemoveLeg(this)">Remove</button></div>'
    +'<div class="tqs-row"><div class="tqs-field"><label>Departure Airport</label><div class="tqs-airport-wrap"><input type="text" class="tqs-airport-search" placeholder="Search airport or city..." /><select name="tqs_mc_from[]" class="tqs-airport-select" onchange="tqsToggleOther(this)">'+oh+'</select><input type="text" name="tqs_mc_from_other[]" class="tqs-airport-other" placeholder="Enter airport name, city or IATA code" style="display:none;" /></div></div>'
    +'<div class="tqs-field"><label>Arrival Airport</label><div class="tqs-airport-wrap"><input type="text" class="tqs-airport-search" placeholder="Search airport or city..." /><select name="tqs_mc_to[]" class="tqs-airport-select" onchange="tqsToggleOther(this)">'+oh+'</select><input type="text" name="tqs_mc_to_other[]" class="tqs-airport-other" placeholder="Enter airport name, city or IATA code" style="display:none;" /></div></div></div>'
    +'<div class="tqs-row tqs-mc-dates-row"><div class="tqs-field tqs-field--date"><label>Departure Date <span class="required">*</span></label><input type="date" name="tqs_mc_dep_date[]" class="tqs-mc-dep-date" min="'+today+'" onchange="tqsValidateLegDates(this)" /></div><div class="tqs-field tqs-field--date"><label>Arrival Date <span class="required">*</span></label><input type="date" name="tqs_mc_arr_date[]" class="tqs-mc-arr-date" min="'+today+'" onchange="tqsValidateLegDates(this)" /></div><div class="tqs-leg-date-error" style="display:none;">Arrival date must be on or after departure date.</div></div>';
  container.appendChild(div);reNumberLegs();
  div.querySelectorAll('.tqs-airport-select').forEach(initSearchOnSelect);
  div.scrollIntoView({behavior:'smooth',block:'center'});
};
window.tqsRemoveLeg=function(btn){
  if(document.querySelectorAll('.tqs-mc-leg').length<=MIN_LEGS)return;
  var leg=btn.closest('.tqs-mc-leg');if(leg){leg.remove();reNumberLegs();}
};
function reNumberLegs(){
  document.querySelectorAll('.tqs-mc-leg').forEach(function(leg,i){
    var b=leg.querySelector('.tqs-leg-badge');if(b)b.textContent='Leg '+(i+1);
    leg.dataset.leg=i;
    var rb=leg.querySelector('.tqs-remove-leg');if(rb)rb.style.display=i<MIN_LEGS?'none':'inline-flex';
  });
  legCount=document.querySelectorAll('.tqs-mc-leg').length;
}

/* ---- CHAR COUNTER ---- */
function initCharCounter(){
  var ta=document.getElementById('tqs_message'),ct=document.getElementById('tqs-char-count');
  if(!ta||!ct)return;var mx=1000;
  function upd(){var l=ta.value.length;if(l>mx){ta.value=ta.value.substring(0,mx);l=mx;}ct.textContent=l;var w=ct.closest('.tqs-char-counter');if(w){w.classList.remove('warn','over');if(l>=mx)w.classList.add('over');else if(l>=mx*.85)w.classList.add('warn');}}
  ta.addEventListener('input',upd);upd();
}

/* ---- FORM GUARD ---- */
function initFormGuard(){
  var form=document.getElementById('tqs-main-form');if(!form)return;
  form.addEventListener('submit',function(e){
    /* Required fields check */
    var missing=[];
    if(!form.tqs_full_name.value.trim()) missing.push('Full Name');
    if(!form.tqs_email.value.trim())     missing.push('Email Address');
    if(!form.tqs_phone.value.trim())     missing.push('Phone Number');
    var at=document.querySelector('input[name="tqs_travel_type"]:checked');
    if(!at) missing.push('Travel Type');
    if(form.tqs_trip_type&&!form.tqs_trip_type.value) missing.push('Trip Category');
    if(form.tqs_cabin&&!form.tqs_cabin.value) missing.push('Cabin Class');
    if(at){
      if(at.value==='oneway'){
        var f1=document.getElementById('tqs_from'),f2=document.getElementById('tqs_destination'),f3=document.getElementById('tqs_travel_date');
        if(!f1||!f1.value) missing.push('Traveling From (airport)');
        if(!f2||!f2.value) missing.push('Destination (airport)');
        if(!f3||!f3.value) missing.push('Departure Date');
      } else if(at.value==='return'){
        var r1=document.getElementById('tqs_from_return'),r2=document.getElementById('tqs_destination_return');
        var r3=document.getElementById('tqs_travel_date_return'),r4=document.getElementById('tqs_return_date');
        if(!r1||!r1.value) missing.push('Traveling From (airport)');
        if(!r2||!r2.value) missing.push('Destination (airport)');
        if(!r3||!r3.value) missing.push('Departure Date');
        if(!r4||!r4.value) missing.push('Return Date');
      } else if(at.value==='multicity'){
        var mf=document.querySelectorAll('[name="tqs_mc_from[]"]');
        var mt=document.querySelectorAll('[name="tqs_mc_to[]"]');
        var md=document.querySelectorAll('[name="tqs_mc_dep_date[]"]');
        mf.forEach(function(el,i){
          if(!el.value)            missing.push('Leg '+(i+1)+' Departure Airport');
          if(mt[i]&&!mt[i].value)  missing.push('Leg '+(i+1)+' Arrival Airport');
          if(md[i]&&!md[i].value)  missing.push('Leg '+(i+1)+' Departure Date');
        });
      }
    }
    if(missing.length){
      e.preventDefault();
      var msg=document.getElementById('tqs-missing-msg');
      if(!msg){msg=document.createElement('div');msg.id='tqs-missing-msg';
        msg.style.cssText='background:#ff4d6d22;border:1px solid #ff4d6d;color:#ff4d6d;padding:14px 18px;border-radius:8px;margin-bottom:18px;';
        form.prepend(msg);}
      msg.innerHTML='';
      var hdr=document.createElement('strong');hdr.textContent='Please fill in all required fields:';msg.appendChild(hdr);
      var ul=document.createElement('ul');ul.style.cssText='margin:8px 0 0 18px';
      missing.forEach(function(f){var li=document.createElement('li');li.textContent=f;ul.appendChild(li);});
      msg.appendChild(ul);
      msg.scrollIntoView({behavior:'smooth',block:'center'});
      return;
    }
    clearPaxError();var blocked=false;
    if(validatePhoneFields())blocked=true;
    var tot=pax.adults+pax.kids+pax.infants;
    if(pax.adults<1){showPaxError('At least 1 adult (12+) is required.');blocked=true;}
    else if(tot>MAX_TOTAL){showPaxError('Total passengers cannot exceed '+MAX_TOTAL+'. You selected '+tot+'.');blocked=true;}
    else if(pax.infants>pax.adults){showPaxError('Infants ('+pax.infants+') cannot exceed adults ('+pax.adults+').');blocked=true;}
    var at=document.querySelector('input[name="tqs_travel_type"]:checked');
    if(at&&at.value==='return'){
      var di=document.getElementById('tqs_travel_date_return'),ai=document.getElementById('tqs_return_date'),ee=document.getElementById('tqs-return-date-error');
      if(di&&ai&&di.value&&ai.value&&ai.value<=di.value){if(ee){ee.textContent='Return date must be after the departure date.';ee.style.display='flex';}if(ai){ai.style.borderColor='var(--error)';ai.style.boxShadow='0 0 0 3px rgba(231,76,60,.18)';}blocked=true;}
    }
    if(at&&at.value==='multicity'){if(validateAllLegDates())blocked=true;}
    if(blocked){
      e.preventDefault();
      var fe=document.querySelector('.tqs-phone-error[style*="block"],.tqs-pax-error[style*="block"],#tqs-return-date-error[style*="flex"],.tqs-leg-date-error[style*="flex"]');
      if(fe)fe.scrollIntoView({behavior:'smooth',block:'center'});
    }
  });
}

/* ---- BOOT ---- */
document.addEventListener('DOMContentLoaded',function(){
  initPaxCounts();initTravelTypeSwitcher();initAirportSearch();
  initLegCount();initFormGuard();initPhoneFields();initDialSelects();initCharCounter();
});
})();
</script>
<?php }

// ============================================================
// AIRPORT DATA
// ============================================================
function tqs_get_airports() {
    return [
        'Netherlands' => [
            'AMS'=>'Amsterdam - Amsterdam Airport Schiphol (AMS)',
            'EIN'=>'Eindhoven - Eindhoven Airport (EIN)',
            'RTM'=>'Rotterdam - Rotterdam The Hague Airport (RTM)',
            'GRQ'=>'Groningen - Groningen Airport Eelde (GRQ)',
            'MST'=>'Maastricht - Maastricht Aachen Airport (MST)',
            'ENS'=>'Enschede - Twente Airport (ENS)',
            'LEY'=>'Lelystad - Lelystad Airport (LEY)',
            'DHR'=>'Den Helder - Den Helder Airport (DHR)',
        ],
        'Belgium' => [
            'BRU'=>'Brussels - Brussels Airport (BRU)',
            'CRL'=>'Charleroi - Brussels South Charleroi Airport (CRL)',
            'LGG'=>'Liege - Liege Airport (LGG)',
            'OST'=>'Ostend - Ostend-Bruges International Airport (OST)',
            'ANR'=>'Antwerp - Antwerp International Airport (ANR)',
            'GNE'=>'Ghent - Ghent Airport (GNE)',
        ],
        'Pakistan' => [
            'ISB'=>'Islamabad - Islamabad International Airport (ISB)',
            'KHI'=>'Karachi - Jinnah International Airport (KHI)',
            'LHE'=>'Lahore - Allama Iqbal International Airport (LHE)',
            'PEW'=>'Peshawar - Bacha Khan International Airport (PEW)',
            'UET'=>'Quetta - Quetta International Airport (UET)',
            'MUX'=>'Multan - Multan International Airport (MUX)',
            'SKT'=>'Sialkot - Sialkot International Airport (SKT)',
            'LYP'=>'Faisalabad - Faisalabad International Airport (LYP)',
            'BHV'=>'Bahawalpur - Bahawalpur Airport (BHV)',
            'RYK'=>'Rahim Yar Khan - Sheikh Zayed International Airport (RYK)',
            'GWD'=>'Gwadar - Gwadar International Airport (GWD)',
            'TUK'=>'Turbat - Turbat International Airport (TUK)',
            'GIL'=>'Gilgit - Gilgit Airport (GIL)',
            'SKZ'=>'Sukkur - Sukkur Airport (SKZ)',
            'HDD'=>'Hyderabad - Hyderabad Airport (HDD)',
            'CWP'=>'Chitral - Chitral Airport (CWP)',
            'WNS'=>'Nawabshah - Shaheed Benazirabad Airport (WNS)',
            'PZH'=>'Zhob - Zhob Airport (PZH)',
        ],
        'Germany' => [
            'FRA'=>'Frankfurt - Frankfurt Airport (FRA)',
            'MUC'=>'Munich - Munich Airport (MUC)',
            'BER'=>'Berlin - Berlin Brandenburg Airport (BER)',
            'DUS'=>'Dusseldorf - Dusseldorf Airport (DUS)',
            'HAM'=>'Hamburg - Hamburg Airport (HAM)',
            'CGN'=>'Cologne - Cologne Bonn Airport (CGN)',
            'STR'=>'Stuttgart - Stuttgart Airport (STR)',
            'NUE'=>'Nuremberg - Nuremberg Airport (NUE)',
            'HAJ'=>'Hannover - Hannover Airport (HAJ)',
            'LEJ'=>'Leipzig - Leipzig Halle Airport (LEJ)',
            'DRS'=>'Dresden - Dresden Airport (DRS)',
            'BRE'=>'Bremen - Bremen Airport (BRE)',
            'HHN'=>'Frankfurt Hahn - Frankfurt Hahn Airport (HHN)',
            'FMO'=>'Munster - Munster Osnabruck Airport (FMO)',
            'NRN'=>'Weeze - Dusseldorf Weeze Airport (NRN)',
            'FDH'=>'Friedrichshafen - Friedrichshafen Airport (FDH)',
            'ERF'=>'Erfurt - Erfurt-Weimar Airport (ERF)',
            'PAD'=>'Paderborn - Paderborn Lippstadt Airport (PAD)',
            'RLG'=>'Rostock - Rostock-Laage Airport (RLG)',
        ],
        'Spain' => [
            'MAD'=>'Madrid - Adolfo Suarez Madrid Barajas (MAD)',
            'BCN'=>'Barcelona - Barcelona El Prat Airport (BCN)',
            'PMI'=>'Palma de Mallorca - Palma Airport (PMI)',
            'AGP'=>'Malaga - Malaga Costa del Sol Airport (AGP)',
            'ALC'=>'Alicante - Alicante Elche Airport (ALC)',
            'VLC'=>'Valencia - Valencia Airport (VLC)',
            'SVQ'=>'Seville - Seville Airport (SVQ)',
            'TFS'=>'Tenerife - Tenerife South Airport (TFS)',
            'TFN'=>'Tenerife - Tenerife North Airport (TFN)',
            'LPA'=>'Gran Canaria - Gran Canaria Airport (LPA)',
            'IBZ'=>'Ibiza - Ibiza Airport (IBZ)',
            'FUE'=>'Fuerteventura - Fuerteventura Airport (FUE)',
            'ACE'=>'Lanzarote - Lanzarote Airport (ACE)',
            'BIO'=>'Bilbao - Bilbao Airport (BIO)',
            'GRO'=>'Girona - Girona Costa Brava Airport (GRO)',
            'MAH'=>'Menorca - Menorca Airport (MAH)',
            'SCQ'=>'Santiago - Santiago de Compostela Airport (SCQ)',
            'ZAZ'=>'Zaragoza - Zaragoza Airport (ZAZ)',
            'GRX'=>'Granada - Federico Garcia Lorca Airport (GRX)',
            'XRY'=>'Jerez - Jerez Airport (XRY)',
        ],
        'Italy' => [
            'FCO'=>'Rome - Leonardo da Vinci Fiumicino (FCO)',
            'CIA'=>'Rome - Ciampino Airport (CIA)',
            'MXP'=>'Milan - Milan Malpensa Airport (MXP)',
            'LIN'=>'Milan - Milan Linate Airport (LIN)',
            'BGY'=>'Milan - Milan Bergamo Airport (BGY)',
            'VCE'=>'Venice - Venice Marco Polo Airport (VCE)',
            'NAP'=>'Naples - Naples International Airport (NAP)',
            'CTA'=>'Catania - Catania Fontanarossa Airport (CTA)',
            'PMO'=>'Palermo - Falcone Borsellino Airport (PMO)',
            'BLQ'=>'Bologna - Bologna Guglielmo Marconi Airport (BLQ)',
            'TRN'=>'Turin - Turin Airport (TRN)',
            'BRI'=>'Bari - Bari Karol Wojtyla Airport (BRI)',
            'FLR'=>'Florence - Florence Airport (FLR)',
            'PSA'=>'Pisa - Galileo Galilei Airport (PSA)',
            'CAG'=>'Cagliari - Cagliari Elmas Airport (CAG)',
            'OLB'=>'Olbia - Olbia Costa Smeralda Airport (OLB)',
            'VRN'=>'Verona - Verona Villafranca Airport (VRN)',
            'TRS'=>'Trieste - Trieste Friuli Venezia Giulia Airport (TRS)',
        ],
        'France' => [
            'CDG'=>'Paris - Charles de Gaulle Airport (CDG)',
            'ORY'=>'Paris - Paris Orly Airport (ORY)',
            'BVA'=>'Paris - Paris Beauvais Tille Airport (BVA)',
            'NCE'=>'Nice - Nice Cote d\'Azur Airport (NCE)',
            'LYS'=>'Lyon - Lyon Saint-Exupery Airport (LYS)',
            'MRS'=>'Marseille - Marseille Provence Airport (MRS)',
            'TLS'=>'Toulouse - Toulouse Blagnac Airport (TLS)',
            'BOD'=>'Bordeaux - Bordeaux Merignac Airport (BOD)',
            'NTE'=>'Nantes - Nantes Atlantique Airport (NTE)',
            'LIL'=>'Lille - Lille Airport (LIL)',
            'SXB'=>'Strasbourg - Strasbourg Airport (SXB)',
            'BSL'=>'Basel - EuroAirport Basel Mulhouse Freiburg (BSL)',
            'AJA'=>'Ajaccio - Napoleon Bonaparte Airport (AJA)',
            'BIA'=>'Bastia - Bastia Poretta Airport (BIA)',
            'MPL'=>'Montpellier - Montpellier Mediterranee Airport (MPL)',
            'GNB'=>'Grenoble - Grenoble Isere Airport (GNB)',
            'LIG'=>'Limoges - Limoges Bellegarde Airport (LIG)',
            'CFE'=>'Clermont-Ferrand - Auvergne Airport (CFE)',
        ],
        'United Kingdom' => [
            'LHR'=>'London - Heathrow Airport (LHR)',
            'LGW'=>'London - Gatwick Airport (LGW)',
            'STN'=>'London - Stansted Airport (STN)',
            'LTN'=>'London - Luton Airport (LTN)',
            'LCY'=>'London - London City Airport (LCY)',
            'MAN'=>'Manchester - Manchester Airport (MAN)',
            'BHX'=>'Birmingham - Birmingham Airport (BHX)',
            'EDI'=>'Edinburgh - Edinburgh Airport (EDI)',
            'GLA'=>'Glasgow - Glasgow Airport (GLA)',
            'BRS'=>'Bristol - Bristol Airport (BRS)',
            'NCL'=>'Newcastle - Newcastle Airport (NCL)',
            'LBA'=>'Leeds - Leeds Bradford Airport (LBA)',
            'BFS'=>'Belfast - Belfast International Airport (BFS)',
            'SOU'=>'Southampton - Southampton Airport (SOU)',
            'ABZ'=>'Aberdeen - Aberdeen Airport (ABZ)',
            'INV'=>'Inverness - Inverness Airport (INV)',
            'EXT'=>'Exeter - Exeter Airport (EXT)',
            'EMA'=>'Nottingham - East Midlands Airport (EMA)',
            'CWL'=>'Cardiff - Cardiff Airport (CWL)',
        ],
        'Ireland' => [
            'DUB'=>'Dublin - Dublin Airport (DUB)',
            'ORK'=>'Cork - Cork Airport (ORK)',
            'SNN'=>'Shannon - Shannon Airport (SNN)',
            'KIR'=>'Kerry - Kerry Airport (KIR)',
            'NOC'=>'Knock - Ireland West Airport Knock (NOC)',
            'CFN'=>'Donegal - Donegal Airport (CFN)',
            'WAT'=>'Waterford - Waterford Airport (WAT)',
        ],
        'Austria' => [
            'VIE'=>'Vienna - Vienna International Airport (VIE)',
            'GRZ'=>'Graz - Graz Airport (GRZ)',
            'SZG'=>'Salzburg - Salzburg Airport (SZG)',
            'INN'=>'Innsbruck - Innsbruck Airport (INN)',
            'LNZ'=>'Linz - Linz Airport (LNZ)',
            'KLU'=>'Klagenfurt - Klagenfurt Airport (KLU)',
        ],
        'Switzerland' => [
            'ZRH'=>'Zurich - Zurich Airport (ZRH)',
            'GVA'=>'Geneva - Geneva Airport (GVA)',
            'BRN'=>'Bern - Bern Airport (BRN)',
            'LUG'=>'Lugano - Lugano Airport (LUG)',
            'SMV'=>'St. Moritz - Samedan Airport (SMV)',
        ],
        'Czech Republic' => [
            'PRG'=>'Prague - Vaclav Havel Airport Prague (PRG)',
            'BRQ'=>'Brno - Brno Turany Airport (BRQ)',
            'OSR'=>'Ostrava - Leos Janacek Airport Ostrava (OSR)',
            'KLV'=>'Karlovy Vary - Karlovy Vary Airport (KLV)',
        ],
        'Slovakia' => [
            'BTS'=>'Bratislava - M. R. Stefanik Airport (BTS)',
            'KSC'=>'Kosice - Kosice Airport (KSC)',
            'TAT'=>'Poprad - Poprad Tatry Airport (TAT)',
        ],
        'Hungary' => [
            'BUD'=>'Budapest - Budapest Ferenc Liszt Airport (BUD)',
            'DEB'=>'Debrecen - Debrecen International Airport (DEB)',
        ],
        'Poland' => [
            'WAW'=>'Warsaw - Warsaw Chopin Airport (WAW)',
            'KRK'=>'Krakow - Krakow John Paul II Airport (KRK)',
            'GDN'=>'Gdansk - Gdansk Lech Walesa Airport (GDN)',
            'WRO'=>'Wroclaw - Copernicus Airport Wroclaw (WRO)',
            'KTW'=>'Katowice - Katowice Airport (KTW)',
            'POZ'=>'Poznan - Poznan Lawica Airport (POZ)',
            'LCJ'=>'Lodz - Lodz Wladyslaw Reymont Airport (LCJ)',
            'RZE'=>'Rzeszow - Rzeszow Jasionka Airport (RZE)',
            'SZZ'=>'Szczecin - Szczecin Goleniow Airport (SZZ)',
        ],
        'Croatia' => [
            'ZAG'=>'Zagreb - Zagreb Airport (ZAG)',
            'SPU'=>'Split - Split Airport (SPU)',
            'DBV'=>'Dubrovnik - Dubrovnik Airport (DBV)',
            'ZAD'=>'Zadar - Zadar Airport (ZAD)',
            'PUY'=>'Pula - Pula Airport (PUY)',
        ],
        'Romania' => [
            'OTP'=>'Bucharest - Henri Coanda International Airport (OTP)',
            'CLJ'=>'Cluj-Napoca - Avram Iancu Cluj Airport (CLJ)',
            'TSR'=>'Timisoara - Traian Vuia International Airport (TSR)',
            'IAS'=>'Iasi - Iasi International Airport (IAS)',
        ],
        'Bulgaria' => [
            'SOF'=>'Sofia - Sofia Airport (SOF)',
            'VAR'=>'Varna - Varna Airport (VAR)',
            'BOJ'=>'Burgas - Burgas Airport (BOJ)',
        ],
        'Serbia' => [
            'BEG'=>'Belgrade - Belgrade Nikola Tesla Airport (BEG)',
            'INI'=>'Nis - Nis Constantine the Great Airport (INI)',
        ],
        'Denmark' => [
            'CPH'=>'Copenhagen - Copenhagen Airport (CPH)',
            'BLL'=>'Billund - Billund Airport (BLL)',
            'AAL'=>'Aalborg - Aalborg Airport (AAL)',
        ],
        'Sweden' => [
            'ARN'=>'Stockholm - Stockholm Arlanda Airport (ARN)',
            'GOT'=>'Gothenburg - Gothenburg Landvetter Airport (GOT)',
            'MMX'=>'Malmo - Malmo Airport (MMX)',
            'UME'=>'Umea - Umea Airport (UME)',
        ],
        'Norway' => [
            'OSL'=>'Oslo - Oslo Airport Gardermoen (OSL)',
            'BGO'=>'Bergen - Bergen Airport Flesland (BGO)',
            'TRD'=>'Trondheim - Trondheim Airport Vaernes (TRD)',
            'SVG'=>'Stavanger - Stavanger Airport Sola (SVG)',
            'TOS'=>'Tromso - Tromso Airport Langnes (TOS)',
        ],
        'Finland' => [
            'HEL'=>'Helsinki - Helsinki Vantaa Airport (HEL)',
            'TMP'=>'Tampere - Tampere Pirkkala Airport (TMP)',
            'OUL'=>'Oulu - Oulu Airport (OUL)',
            'RVN'=>'Rovaniemi - Rovaniemi Airport (RVN)',
        ],
        'Luxembourg' => [
            'LUX'=>'Luxembourg - Luxembourg Airport (LUX)',
        ],
        'Portugal' => [
            'LIS'=>'Lisbon - Humberto Delgado Airport (LIS)',
            'OPO'=>'Porto - Francisco Sa Carneiro Airport (OPO)',
            'FAO'=>'Faro - Faro Airport (FAO)',
            'FNC'=>'Funchal - Madeira Airport (FNC)',
        ],
        'Greece' => [
            'ATH'=>'Athens - Athens International Airport (ATH)',
            'SKG'=>'Thessaloniki - Thessaloniki Airport (SKG)',
            'HER'=>'Heraklion - Heraklion International Airport (HER)',
            'RHO'=>'Rhodes - Rhodes International Airport (RHO)',
            'CFU'=>'Corfu - Corfu International Airport (CFU)',
            'JMK'=>'Mykonos - Mykonos Airport (JMK)',
            'JTR'=>'Santorini - Santorini Airport (JTR)',
            'CHQ'=>'Chania - Chania International Airport (CHQ)',
            'KGS'=>'Kos - Kos Island International Airport (KGS)',
        ],
    ];
}

// ============================================================
// DIAL CODES
// ============================================================
function tqs_get_dial_codes() {
    return [
        'Netherlands (+31)'=>'+31','Belgium (+32)'=>'+32','Pakistan (+92)'=>'+92','Other'=>'other',
        'Afghanistan (+93)'=>'+93','Albania (+355)'=>'+355','Algeria (+213)'=>'+213','Argentina (+54)'=>'+54',
        'Australia (+61)'=>'+61','Austria (+43)'=>'+43','Azerbaijan (+994)'=>'+994','Bahrain (+973)'=>'+973',
        'Bangladesh (+880)'=>'+880','Belarus (+375)'=>'+375','Bosnia (+387)'=>'+387','Brazil (+55)'=>'+55',
        'Bulgaria (+359)'=>'+359','Canada (+1)'=>'+1','China (+86)'=>'+86','Croatia (+385)'=>'+385',
        'Czech Republic (+420)'=>'+420','Cyprus (+357)'=>'+357','Denmark (+45)'=>'+45','Egypt (+20)'=>'+20',
        'Ethiopia (+251)'=>'+251','Finland (+358)'=>'+358','France (+33)'=>'+33','Georgia (+995)'=>'+995',
        'Germany (+49)'=>'+49','Ghana (+233)'=>'+233','Greece (+30)'=>'+30','Hungary (+36)'=>'+36',
        'India (+91)'=>'+91','Indonesia (+62)'=>'+62','Iran (+98)'=>'+98','Iraq (+964)'=>'+964',
        'Ireland (+353)'=>'+353','Israel (+972)'=>'+972','Italy (+39)'=>'+39','Japan (+81)'=>'+81',
        'Jordan (+962)'=>'+962','Kazakhstan (+7)'=>'+7','Kenya (+254)'=>'+254','Kuwait (+965)'=>'+965',
        'Kyrgyzstan (+996)'=>'+996','Latvia (+371)'=>'+371','Lebanon (+961)'=>'+961','Libya (+218)'=>'+218',
        'Lithuania (+370)'=>'+370','Luxembourg (+352)'=>'+352','Malaysia (+60)'=>'+60','Malta (+356)'=>'+356',
        'Mexico (+52)'=>'+52','Moldova (+373)'=>'+373','Morocco (+212)'=>'+212','New Zealand (+64)'=>'+64',
        'Nigeria (+234)'=>'+234','North Macedonia (+389)'=>'+389','Norway (+47)'=>'+47','Oman (+968)'=>'+968',
        'Philippines (+63)'=>'+63','Poland (+48)'=>'+48','Portugal (+351)'=>'+351','Qatar (+974)'=>'+974',
        'Romania (+40)'=>'+40','Russia (+7)'=>'+7','Saudi Arabia (+966)'=>'+966','Serbia (+381)'=>'+381',
        'Singapore (+65)'=>'+65','Slovakia (+421)'=>'+421','Slovenia (+386)'=>'+386','Somalia (+252)'=>'+252',
        'South Africa (+27)'=>'+27','South Korea (+82)'=>'+82','Spain (+34)'=>'+34','Sri Lanka (+94)'=>'+94',
        'Sudan (+249)'=>'+249','Sweden (+46)'=>'+46','Switzerland (+41)'=>'+41','Syria (+963)'=>'+963',
        'Taiwan (+886)'=>'+886','Tajikistan (+992)'=>'+992','Tanzania (+255)'=>'+255','Thailand (+66)'=>'+66',
        'Tunisia (+216)'=>'+216','Turkey (+90)'=>'+90','Turkmenistan (+993)'=>'+993','UAE (+971)'=>'+971',
        'Uganda (+256)'=>'+256','Ukraine (+380)'=>'+380','United Kingdom (+44)'=>'+44','USA (+1)'=>'+1',
        'Uzbekistan (+998)'=>'+998','Yemen (+967)'=>'+967','Zimbabwe (+263)'=>'+263',
    ];
}

// ============================================================
// SHORTCODE
// ============================================================
add_shortcode( 'tqs_inquiry_form', 'tqs_render_inquiry_form' );
function tqs_render_inquiry_form() {
    ob_start();
    $submitted = false;
    $errors    = [];

    if ( isset( $_POST['tqs_submit'] ) ) {
        if ( ! isset( $_POST['tqs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tqs_nonce'] ) ), 'tqs_inquiry_action' ) ) {
            $errors[] = 'Security check failed. Please try again.';
        } else {
            $full_name        = sanitize_text_field( wp_unslash( $_POST['tqs_full_name']       ?? '' ) );
            $email            = sanitize_email(      wp_unslash( $_POST['tqs_email']            ?? '' ) );
            $phone_code       = sanitize_text_field( wp_unslash( $_POST['tqs_phone_code']       ?? '' ) );
            $phone_code_other = sanitize_text_field( wp_unslash( $_POST['tqs_phone_code_other'] ?? '' ) );
            $phone_digits     = preg_replace( '/\D/', '', wp_unslash( $_POST['tqs_phone'] ?? '' ) );
            $final_phone_code = $phone_code === 'other' ? $phone_code_other : $phone_code;
            $full_phone       = $final_phone_code . $phone_digits;

            $wa_same       = isset( $_POST['tqs_wa_same'] );
            $wa_code       = sanitize_text_field( wp_unslash( $_POST['tqs_wa_code']       ?? '' ) );
            $wa_code_other = sanitize_text_field( wp_unslash( $_POST['tqs_wa_code_other'] ?? '' ) );
            $wa_digits     = preg_replace( '/\D/', '', wp_unslash( $_POST['tqs_whatsapp'] ?? '' ) );
            $final_wa_code = $wa_code === 'other' ? $wa_code_other : $wa_code;
            $full_whatsapp = $wa_same ? $full_phone : ( $final_wa_code . $wa_digits );

            $travel_type    = sanitize_text_field( wp_unslash( $_POST['tqs_travel_type'] ?? '' ) );
            $trip_type      = sanitize_text_field( wp_unslash( $_POST['tqs_trip_type']   ?? '' ) );
            $cabin          = sanitize_text_field( wp_unslash( $_POST['tqs_cabin']       ?? '' ) );
            $budget         = sanitize_text_field( wp_unslash( $_POST['tqs_budget']      ?? '' ) );
            $services       = isset( $_POST['tqs_services'] )       ? array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) $_POST['tqs_services'] ) )       : [];
            $quick_requests = isset( $_POST['tqs_quick_requests'] ) ? array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) $_POST['tqs_quick_requests'] ) ) : [];
            $message        = sanitize_textarea_field( wp_unslash( $_POST['tqs_message'] ?? '' ) );

            $adults  = max( 1, intval( $_POST['tqs_adults']  ?? 1 ) );
            $kids    = max( 0, intval( $_POST['tqs_kids']    ?? 0 ) );
            $infants = max( 0, intval( $_POST['tqs_infants'] ?? 0 ) );
            $total   = $adults + $kids + $infants;

            // One Way fields
            $from        = sanitize_text_field( wp_unslash( $_POST['tqs_from']              ?? '' ) );
            $from_other  = sanitize_text_field( wp_unslash( $_POST['tqs_from_other']        ?? '' ) );
            $destination = sanitize_text_field( wp_unslash( $_POST['tqs_destination']       ?? '' ) );
            $dest_other  = sanitize_text_field( wp_unslash( $_POST['tqs_destination_other'] ?? '' ) );
            $travel_date = sanitize_text_field( wp_unslash( $_POST['tqs_travel_date']       ?? '' ) );

            // Return fields
            $return_from       = sanitize_text_field( wp_unslash( $_POST['tqs_from_return']               ?? '' ) );
            $return_from_other = sanitize_text_field( wp_unslash( $_POST['tqs_from_return_other']         ?? '' ) );
            $return_dest       = sanitize_text_field( wp_unslash( $_POST['tqs_destination_return']        ?? '' ) );
            $return_dest_other = sanitize_text_field( wp_unslash( $_POST['tqs_destination_return_other']  ?? '' ) );
            $return_dep        = sanitize_text_field( wp_unslash( $_POST['tqs_travel_date_return']        ?? '' ) );
            $return_arr        = sanitize_text_field( wp_unslash( $_POST['tqs_return_date']               ?? '' ) );

            $mc_legs = [];
            if ( $travel_type === 'multicity' ) {
                $mc_froms       = array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) ( $_POST['tqs_mc_from']       ?? [] ) ) );
                $mc_tos         = array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) ( $_POST['tqs_mc_to']         ?? [] ) ) );
                $mc_dep_dates   = array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) ( $_POST['tqs_mc_dep_date']   ?? [] ) ) );
                $mc_arr_dates   = array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) ( $_POST['tqs_mc_arr_date']   ?? [] ) ) );
                $mc_from_others = array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) ( $_POST['tqs_mc_from_other'] ?? [] ) ) );
                $mc_to_others   = array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) ( $_POST['tqs_mc_to_other']   ?? [] ) ) );
                foreach ( $mc_froms as $i => $mf ) {
                    $dep = $mc_dep_dates[$i] ?? ''; $arr = $mc_arr_dates[$i] ?? '';
                    if ( $dep && $arr && $arr < $dep ) $errors[] = 'Leg ' . ($i+1) . ': Arrival date cannot be before departure date.';
                    $mfv = ($mf === 'other' || empty($mf)) ? ($mc_from_others[$i] ?? '') : $mf;
                    $mtv = (($mc_tos[$i] ?? '') === 'other' || empty($mc_tos[$i] ?? '')) ? ($mc_to_others[$i] ?? '') : ($mc_tos[$i] ?? '');
                    if ( empty($mfv) ) $errors[] = 'Leg ' . ($i+1) . ': Departure airport is required.';
                    if ( empty($mtv) ) $errors[] = 'Leg ' . ($i+1) . ': Arrival airport is required.';
                    if ( empty($dep) ) $errors[] = 'Leg ' . ($i+1) . ': Departure date is required.';
                    $mc_legs[] = [ 'from'=>$mf, 'from_other'=>$mc_from_others[$i]??'', 'to'=>$mc_tos[$i]??'', 'to_other'=>$mc_to_others[$i]??'', 'dep_date'=>$dep, 'arr_date'=>$arr ];
                }
            }

            if ( empty($full_name) )                                      $errors[] = 'Full name is required.';
            if ( empty($email) || ! is_email($email) )                    $errors[] = 'A valid email address is required.';
            if ( empty($phone_digits) )                                   $errors[] = 'Phone number is required.';
            elseif ( strlen($phone_digits)<4||strlen($phone_digits)>15 )  $errors[] = 'Phone number must be between 4 and 15 digits.';
            elseif ( empty($final_phone_code) )                           $errors[] = 'Please select a country dialing code for your phone number.';
            if ( !$wa_same && !empty($wa_digits) ) {
                if ( strlen($wa_digits)<4||strlen($wa_digits)>15 )        $errors[] = 'WhatsApp number must be between 4 and 15 digits.';
                elseif ( empty($final_wa_code) )                          $errors[] = 'Please select a country dialing code for your WhatsApp number.';
            }
            if ( empty($travel_type) )                                    $errors[] = 'Please select a travel type.';
            if ( $travel_type === 'oneway' ) {
                $fv = ($from === 'other' || empty($from)) ? $from_other : $from;
                $dv = ($destination==='other'||empty($destination)) ? $dest_other : $destination;
                if ( empty($fv) )          $errors[] = 'Origin airport is required.';
                if ( empty($dv) )          $errors[] = 'Destination airport is required.';
                if ( empty($travel_date) ) $errors[] = 'Departure date is required.';
            }
            if ( $travel_type === 'return' ) {
                $rfv = ($return_from === 'other' || empty($return_from)) ? $return_from_other : $return_from;
                $rdv = ($return_dest==='other'||empty($return_dest)) ? $return_dest_other : $return_dest;
                if ( empty($rfv) )         $errors[] = 'Origin airport is required.';
                if ( empty($rdv) )         $errors[] = 'Destination airport is required.';
                if ( empty($return_dep) )  $errors[] = 'Departure date is required.';
                if ( empty($return_arr) )  $errors[] = 'Return date is required.';
                if ( !empty($return_dep)&&!empty($return_arr)&&$return_arr<=$return_dep ) $errors[] = 'Return date must be after the departure date.';
            }
            if ( $travel_type==='multicity'&&count($mc_legs)<3 ) $errors[] = 'Please add at least 3 legs for a Multi-City trip.';
            if ( empty($trip_type) )    $errors[] = 'Please select a trip category.';
            if ( empty($cabin) )        $errors[] = 'Please select a cabin class.';
            if ( $adults<1 )        $errors[] = 'At least 1 adult (12+) is required.';
            if ( $total>9 )         $errors[] = 'Total passengers cannot exceed 9. You selected '.$total.'.';
            if ( $infants>$adults ) $errors[] = 'Infants ('.$infants.') cannot exceed adults ('.$adults.').';

            if ( empty($errors) ) {
                $submitted = tqs_send_inquiry_email(
                    $full_name, $email, $full_phone, $full_whatsapp, $travel_type,
                    $from, $from_other, $destination, $dest_other,
                    $return_from, $return_from_other, $return_dest, $return_dest_other,
                    $travel_date, $return_dep, $return_arr, $mc_legs,
                    $adults, $kids, $infants, $trip_type, $cabin, $budget, $services,
                    $quick_requests, $message
                );
            }
        }
    }

    if ( $submitted ) {
        echo '<div class="tqs-success"><h3>Thank you for your inquiry!</h3><p>Our team at <strong>TQS Travels</strong> will get back to you within 24 hours.</p></div>';
    } else {
        if ( !empty($errors) ) {
            echo '<div class="tqs-errors"><ul>';
            foreach ( $errors as $e ) echo '<li>'.esc_html($e).'</li>';
            echo '</ul></div>';
        }
        tqs_render_form();
    }
    return ob_get_clean();
}

// ============================================================
// FORM RENDER
// ============================================================
function tqs_render_form() {
    $airports   = tqs_get_airports();
    $dial_codes = tqs_get_dial_codes();

    $pax_adult  = intval( $_POST['tqs_adults']  ?? 1 );
    $pax_kids   = intval( $_POST['tqs_kids']    ?? 0 );
    $pax_infant = intval( $_POST['tqs_infants'] ?? 0 );

    $sel_phone_code = sanitize_text_field( wp_unslash( $_POST['tqs_phone_code'] ?? '+31' ) );
    $sel_wa_code    = sanitize_text_field( wp_unslash( $_POST['tqs_wa_code']    ?? '+31' ) );
    $wa_same        = isset($_POST['tqs_submit']) ? isset($_POST['tqs_wa_same']) : true;
    $sel_type       = sanitize_text_field( wp_unslash( $_POST['tqs_travel_type'] ?? 'oneway' ) );
    ?>
<div class="tqs-form-wrapper">
  <div class="tqs-form-header">
    <h2>Plan Your Dream Trip with TQS Travels</h2>
    <p>Fill in your travel requirements and our team will get back to you within 24 hours.</p>
  </div>
  <form method="POST" class="tqs-inquiry-form" id="tqs-main-form" novalidate>
    <?php wp_nonce_field('tqs_inquiry_action','tqs_nonce'); ?>

    <!-- PERSONAL INFORMATION -->
    <div class="tqs-form-section"><h3>Personal Information</h3>
      <div class="tqs-row">
        <div class="tqs-field">
          <label for="tqs_full_name">Full Name <span class="required">*</span></label>
          <input type="text" id="tqs_full_name" name="tqs_full_name" value="<?php echo esc_attr($_POST['tqs_full_name']??''); ?>" placeholder="e.g. John Smith" required />
        </div>
        <div class="tqs-field">
          <label for="tqs_email">Email Address <span class="required">*</span></label>
          <input type="email" id="tqs_email" name="tqs_email" value="<?php echo esc_attr($_POST['tqs_email']??''); ?>" placeholder="e.g. john@email.com" required />
        </div>
      </div>
      <div class="tqs-row"><div class="tqs-field">
        <label>Phone Number <span class="required">*</span></label>
        <div class="tqs-phone-wrap">
          <select name="tqs_phone_code" id="tqs_phone_code" class="tqs-dial-select" onchange="tqsSyncWaCode(this)">
            <?php foreach($dial_codes as $dl=>$dc): $io=($dc==='other'); ?>
            <option value="<?php echo esc_attr($io?'other':$dc); ?>" <?php selected($sel_phone_code,$io?'other':$dc); ?>><?php echo esc_html($dl); ?></option>
            <?php endforeach; ?>
          </select>
          <input type="text" name="tqs_phone_code_other" id="tqs_phone_code_other" class="tqs-dial-other" placeholder="+00" value="<?php echo esc_attr($_POST['tqs_phone_code_other']??''); ?>" style="<?php echo $sel_phone_code==='other'?'display:flex;':'display:none;'; ?>" maxlength="6" />
          <input type="tel" name="tqs_phone" id="tqs_phone" class="tqs-phone-number" placeholder="Digits only e.g. 612345678" value="<?php echo esc_attr($_POST['tqs_phone']??''); ?>" required inputmode="numeric" pattern="[0-9]{4,15}" />
        </div>
        <span class="tqs-field-hint">Numbers only - no spaces, dashes or brackets</span>
        <div class="tqs-phone-error" id="tqs-phone-error" style="display:none;">Please enter digits only (e.g. 612345678)</div>
      </div></div>
      <div class="tqs-row"><div class="tqs-field">
        <label>WhatsApp Number <span class="tqs-optional-tag">optional</span></label>
        <label class="tqs-wa-same-label">
          <input type="checkbox" name="tqs_wa_same" id="tqs_wa_same" value="1" <?php checked($wa_same,true); ?> onchange="tqsToggleWa(this)" />
          Same as phone number
        </label>
        <div class="tqs-phone-wrap" id="tqs-wa-fields" style="<?php echo $wa_same?'display:none;':'display:flex;'; ?> margin-top:10px;">
          <select name="tqs_wa_code" id="tqs_wa_code" class="tqs-dial-select">
            <?php foreach($dial_codes as $dl=>$dc): $io=($dc==='other'); ?>
            <option value="<?php echo esc_attr($io?'other':$dc); ?>" <?php selected($sel_wa_code,$io?'other':$dc); ?>><?php echo esc_html($dl); ?></option>
            <?php endforeach; ?>
          </select>
          <input type="text" name="tqs_wa_code_other" id="tqs_wa_code_other" class="tqs-dial-other" placeholder="+00" value="<?php echo esc_attr($_POST['tqs_wa_code_other']??''); ?>" style="<?php echo (isset($_POST['tqs_wa_code'])&&$_POST['tqs_wa_code']==='other')?'display:flex;':'display:none;'; ?>" maxlength="6" />
          <input type="tel" name="tqs_whatsapp" id="tqs_whatsapp" class="tqs-phone-number" placeholder="Digits only e.g. 612345678" value="<?php echo esc_attr($_POST['tqs_whatsapp']??''); ?>" inputmode="numeric" pattern="[0-9]{4,15}" />
        </div>
        <div class="tqs-phone-error" id="tqs-wa-error" style="display:none;">Please enter digits only (e.g. 612345678)</div>
      </div></div>
    </div>

    <!-- TRAVEL TYPE -->
    <div class="tqs-form-section"><h3>Travel Type <span class="required">*</span></h3>
      <div class="tqs-travel-type-selector">
        <?php foreach(['oneway'=>'One Way','return'=>'Return','multicity'=>'Multi-City'] as $v=>$l): ?>
        <label class="tqs-type-card <?php echo $sel_type===$v?'active':''; ?>">
          <input type="radio" name="tqs_travel_type" value="<?php echo esc_attr($v); ?>" <?php checked($sel_type,$v); ?> />
          <span class="tqs-type-label"><?php echo esc_html($l); ?></span>
        </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ONE WAY -->
    <div class="tqs-form-section tqs-type-section" id="tqs-section-oneway"><h3>Flight Details - One Way</h3>
      <div class="tqs-row">
        <div class="tqs-field"><?php tqs_render_airport_field(['label'=>'Traveling From','name'=>'tqs_from','id'=>'tqs_from','airports'=>$airports,'selected'=>sanitize_text_field($_POST['tqs_from']??''),'other_val'=>sanitize_text_field($_POST['tqs_from_other']??''),'other_name'=>'tqs_from_other','required'=>true]); ?></div>
        <div class="tqs-field"><?php tqs_render_airport_field(['label'=>'Destination','name'=>'tqs_destination','id'=>'tqs_destination','airports'=>$airports,'selected'=>sanitize_text_field($_POST['tqs_destination']??''),'other_val'=>sanitize_text_field($_POST['tqs_destination_other']??''),'other_name'=>'tqs_destination_other','required'=>true]); ?></div>
      </div>
      <div class="tqs-row"><div class="tqs-field" style="max-width:280px;">
        <label for="tqs_travel_date">Departure Date <span class="required">*</span></label>
        <input type="date" id="tqs_travel_date" name="tqs_travel_date" value="<?php echo esc_attr($_POST['tqs_travel_date']??''); ?>" min="<?php echo esc_attr(date('Y-m-d')); ?>" required />
      </div></div>
    </div>

    <!-- RETURN -->
    <div class="tqs-form-section tqs-type-section" id="tqs-section-return"><h3>Flight Details - Return</h3>
      <div class="tqs-row">
        <div class="tqs-field"><?php tqs_render_airport_field(['label'=>'Traveling From','name'=>'tqs_from_return','id'=>'tqs_from_return','airports'=>$airports,'selected'=>sanitize_text_field($_POST['tqs_from_return']??''),'other_val'=>sanitize_text_field($_POST['tqs_from_return_other']??''),'other_name'=>'tqs_from_return_other','required'=>true]); ?></div>
        <div class="tqs-field"><?php tqs_render_airport_field(['label'=>'Destination','name'=>'tqs_destination_return','id'=>'tqs_destination_return','airports'=>$airports,'selected'=>sanitize_text_field($_POST['tqs_destination_return']??''),'other_val'=>sanitize_text_field($_POST['tqs_destination_return_other']??''),'other_name'=>'tqs_destination_return_other','required'=>true]); ?></div>
      </div>
      <div class="tqs-row">
        <div class="tqs-field"><label>Departure Date <span class="required">*</span></label><input type="date" name="tqs_travel_date_return" id="tqs_travel_date_return" value="<?php echo esc_attr($_POST['tqs_travel_date_return']??''); ?>" min="<?php echo esc_attr(date('Y-m-d')); ?>" onchange="tqsReturnDepChanged(this)" required /></div>
        <div class="tqs-field"><label>Return Date <span class="required">*</span></label><input type="date" name="tqs_return_date" id="tqs_return_date" value="<?php echo esc_attr($_POST['tqs_return_date']??''); ?>" min="<?php echo esc_attr($_POST['tqs_travel_date_return']??date('Y-m-d')); ?>" onchange="tqsReturnArrChanged(this)" required /></div>
      </div>
      <div id="tqs-return-date-error" class="tqs-return-date-error" style="display:none;">Return date must be after the departure date.</div>
    </div>

    <!-- MULTI-CITY -->
    <div class="tqs-form-section tqs-type-section" id="tqs-section-multicity"><h3>Multi-City Legs</h3>
      <p class="tqs-hint">Minimum 3 legs required. Click "Add Another Leg" to add more stops.</p>
      <div id="tqs-mc-legs">
        <?php
        $mcf=(array)($_POST['tqs_mc_from']??['','','']);$mct=(array)($_POST['tqs_mc_to']??['','','']);
        $mcd=(array)($_POST['tqs_mc_dep_date']??['','','']);$mca=(array)($_POST['tqs_mc_arr_date']??['','','']);
        $mcfo=(array)($_POST['tqs_mc_from_other']??[]);$mcto=(array)($_POST['tqs_mc_to_other']??[]);
        $mlc=max(count($mcf),3);
        for($i=0;$i<$mlc;$i++): ?>
        <div class="tqs-mc-leg" data-leg="<?php echo esc_attr($i); ?>">
          <div class="tqs-mc-leg-header">
            <span class="tqs-leg-badge">Leg <?php echo intval($i+1); ?></span>
            <?php if($i>=3): ?><button type="button" class="tqs-remove-leg" onclick="tqsRemoveLeg(this)">Remove</button><?php endif; ?>
          </div>
          <div class="tqs-row">
            <div class="tqs-field"><?php tqs_render_airport_field(['label'=>'Departure Airport','name'=>'tqs_mc_from[]','id'=>'tqs_mc_from_'.$i,'airports'=>$airports,'selected'=>sanitize_text_field($mcf[$i]??''),'other_val'=>sanitize_text_field($mcfo[$i]??''),'other_name'=>'tqs_mc_from_other[]','required'=>true]); ?></div>
            <div class="tqs-field"><?php tqs_render_airport_field(['label'=>'Arrival Airport','name'=>'tqs_mc_to[]','id'=>'tqs_mc_to_'.$i,'airports'=>$airports,'selected'=>sanitize_text_field($mct[$i]??''),'other_val'=>sanitize_text_field($mcto[$i]??''),'other_name'=>'tqs_mc_to_other[]','required'=>true]); ?></div>
          </div>
          <div class="tqs-row tqs-mc-dates-row">
            <div class="tqs-field tqs-field--date"><label>Departure Date <span class="required">*</span></label><input type="date" name="tqs_mc_dep_date[]" class="tqs-mc-dep-date" value="<?php echo esc_attr($mcd[$i]??''); ?>" min="<?php echo esc_attr(date('Y-m-d')); ?>" onchange="tqsValidateLegDates(this)" required /></div>
            <div class="tqs-field tqs-field--date"><label>Arrival Date <span class="required">*</span></label><input type="date" name="tqs_mc_arr_date[]" class="tqs-mc-arr-date" value="<?php echo esc_attr($mca[$i]??''); ?>" min="<?php echo esc_attr(date('Y-m-d')); ?>" onchange="tqsValidateLegDates(this)" /></div>
            <div class="tqs-leg-date-error" style="display:none;">Arrival date must be on or after departure date.</div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
      <button type="button" class="tqs-add-leg-btn" onclick="tqsAddLeg()">+ Add Another Leg</button>
    </div>

    <!-- PASSENGERS -->
    <div class="tqs-form-section"><h3>Passengers</h3>
      <p class="tqs-hint">Maximum 9 passengers total. Infants cannot exceed the number of adults.</p>
      <div class="tqs-pax-grid">
        <?php foreach(['adult'=>['id'=>'adults','label'=>'Adults','age'=>'12+ years','val'=>$pax_adult],'kids'=>['id'=>'kids','label'=>'Children','age'=>'2 - 11 years','val'=>$pax_kids],'infants'=>['id'=>'infants','label'=>'Infants','age'=>'Under 2 years','val'=>$pax_infant]] as $slug=>$c): ?>
        <div class="tqs-pax-card <?php echo $c['val']>0?'pax-active':''; ?>" id="pax-card-<?php echo esc_attr($slug); ?>">
          <div class="tqs-pax-info"><div class="tqs-pax-type"><?php echo esc_html($c['label']); ?></div><div class="tqs-pax-age"><?php echo esc_html($c['age']); ?></div></div>
          <div class="tqs-pax-counter">
            <button type="button" class="tqs-pax-btn tqs-pax-minus" onclick="tqsChangePax('<?php echo esc_attr($c['id']); ?>',-1)">-</button>
            <span class="tqs-pax-count" id="pax-count-<?php echo esc_attr($c['id']); ?>"><?php echo intval($c['val']); ?></span>
            <input type="hidden" name="tqs_<?php echo esc_attr($c['id']); ?>" id="tqs_<?php echo esc_attr($c['id']); ?>" value="<?php echo intval($c['val']); ?>" />
            <button type="button" class="tqs-pax-btn tqs-pax-plus" onclick="tqsChangePax('<?php echo esc_attr($c['id']); ?>',1)">+</button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="tqs-pax-summary" id="tqs-pax-summary"><span id="tqs-pax-summary-text">
        <?php $tot=$pax_adult+$pax_kids+$pax_infant;
        echo 'Total: <strong>'.intval($tot).' / 9</strong> passengers &nbsp;&middot;&nbsp; Adults: <strong>'.intval($pax_adult).'</strong> &nbsp;&middot;&nbsp; Children: <strong>'.intval($pax_kids).'</strong> &nbsp;&middot;&nbsp; Infants: <strong>'.intval($pax_infant).'</strong>'; ?>
      </span></div>
      <div class="tqs-pax-error" id="tqs-pax-error" style="display:none;"></div>
    </div>

    <!-- TRIP PREFERENCES -->
    <div class="tqs-form-section"><h3>Trip Preferences</h3>
      <div class="tqs-row">
        <div class="tqs-field"><label for="tqs_trip_type">Trip Category <span class="required">*</span></label>
          <select id="tqs_trip_type" name="tqs_trip_type" required><option value="">-- Select --</option>
            <?php $stt=sanitize_text_field($_POST['tqs_trip_type']??'');
            foreach(['Leisure / Holiday','Honeymoon','Family Trip','Adventure','Business Travel','Group Tour','Solo Travel','Pilgrimage'] as $t) printf('<option value="%s"%s>%s</option>',esc_attr($t),selected($stt,$t,false),esc_html($t)); ?>
          </select></div>
        <div class="tqs-field"><label for="tqs_cabin">Cabin Class <span class="required">*</span></label>
          <select id="tqs_cabin" name="tqs_cabin" required><option value="">-- Select Cabin --</option>
            <?php $scb=sanitize_text_field($_POST['tqs_cabin']??'');
            foreach(['Economy','Premium Economy','Business','First Class'] as $c) printf('<option value="%s"%s>%s</option>',esc_attr($c),selected($scb,$c,false),esc_html($c)); ?>
          </select></div>
        <div class="tqs-field"><label for="tqs_budget">Approximate Budget (per person)</label>
          <select id="tqs_budget" name="tqs_budget"><option value="">-- Select --</option>
            <?php $sb=sanitize_text_field($_POST['tqs_budget']??'');
            foreach(['Under $500','$500 - $1,000','$1,000 - $2,500','$2,500 - $5,000','$5,000 - $10,000','$10,000+','Flexible'] as $b) printf('<option value="%s"%s>%s</option>',esc_attr($b),selected($sb,$b,false),esc_html($b)); ?>
          </select></div>
      </div>
      <div class="tqs-field tqs-checkboxes"><label>Services Required</label>
        <div class="tqs-checkbox-grid">
          <?php $ss=(array)($_POST['tqs_services']??[]);
          foreach(['Flight Booking','Hotel / Accommodation','Airport Transfer','Car Rental','Tour Guide','Travel Insurance','Visa Assistance','Cruise Booking','All-Inclusive Package'] as $s): $chk=in_array($s,$ss,true)?'checked':''; ?>
          <label class="tqs-checkbox-label"><input type="checkbox" name="tqs_services[]" value="<?php echo esc_attr($s); ?>" <?php echo $chk; ?>><?php echo esc_html($s); ?></label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- SPECIAL REQUESTS -->
    <div class="tqs-form-section"><h3>Special Requests and Comments</h3>
      <p class="tqs-hint">Let us know anything specific - we will do our best to accommodate your needs.</p>
      <div class="tqs-comments-grid">
        <div class="tqs-field tqs-checkboxes"><label>Quick Requests <span class="tqs-optional-tag">optional</span></label>
          <div class="tqs-checkbox-grid">
            <?php $sqr=(array)($_POST['tqs_quick_requests']??[]);
            foreach(['Vegetarian / Vegan Meal','Halal Meal','Baby / Infant Meal','Wheelchair Assistance','Extra Legroom Seat','Window Seat Preferred','Hotel Recommendation Needed','Airport Transfer Required','Visa Assistance Required','Special Occasion (Birthday / Anniversary)','Travelling with Medical Equipment','Travelling with Pet'] as $qr): $chk=in_array($qr,$sqr,true)?'checked':''; ?>
            <label class="tqs-checkbox-label"><input type="checkbox" name="tqs_quick_requests[]" value="<?php echo esc_attr($qr); ?>" <?php echo $chk; ?>><?php echo esc_html($qr); ?></label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="tqs-field">
          <label for="tqs_message">Additional Comments / Special Requests <span class="tqs-optional-tag">optional</span></label>
          <textarea id="tqs_message" name="tqs_message" rows="6" class="tqs-comments-box" placeholder="e.g. We are celebrating our honeymoon and would love a window seat..."><?php echo esc_textarea($_POST['tqs_message']??''); ?></textarea>
          <div class="tqs-char-counter"><span id="tqs-char-count">0</span> / 1000 characters</div>
        </div>
      </div>
    </div>

    <div class="tqs-submit-row">
      <button type="submit" name="tqs_submit" class="tqs-submit-btn">Send My Travel Inquiry</button>
    </div>
  </form>
</div>
    <?php
}

// ============================================================
// AIRPORT FIELD RENDERER
// ============================================================
function tqs_render_airport_field( $args ) {
    $label=$args['label']??'Airport'; $name=$args['name']??'airport'; $id=$args['id']??'airport';
    $airports=$args['airports']??[]; $selected=$args['selected']??''; $other_val=$args['other_val']??'';
    $other_name=$args['other_name']??$name.'_other'; $required=!empty($args['required']);
    $req_star=$required?'<span class="required">*</span>':''; $show_other=($selected==='other');
    $priority=['Netherlands','Belgium','Pakistan']; $top=[]; foreach($priority as $c){if(isset($airports[$c]))$top[$c]=$airports[$c];}
    $rest=array_diff_key($airports,array_flip($priority));
    ?>
    <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?> <?php echo $req_star; ?></label>
    <div class="tqs-airport-wrap">
      <input type="text" class="tqs-airport-search" placeholder="Search airport or city..." />
      <select name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" class="tqs-airport-select" onchange="tqsToggleOther(this)" <?php echo $required?'required':''; ?>>
        <option value="">-- Select Airport --</option>
        <?php foreach($top as $country=>$al): ?><optgroup label="<?php echo esc_attr($country); ?>"><?php foreach($al as $code=>$an): ?><option value="<?php echo esc_attr($code); ?>" <?php selected($selected,$code); ?>><?php echo esc_html($an); ?></option><?php endforeach; ?></optgroup><?php endforeach; ?>
        <optgroup label="Other"><option value="other" <?php selected($selected,'other'); ?>>Other - Enter Manually</option></optgroup>
        <?php foreach($rest as $country=>$al): ?><optgroup label="<?php echo esc_attr($country); ?>"><?php foreach($al as $code=>$an): ?><option value="<?php echo esc_attr($code); ?>" <?php selected($selected,$code); ?>><?php echo esc_html($an); ?></option><?php endforeach; ?></optgroup><?php endforeach; ?>
      </select>
      <input type="text" name="<?php echo esc_attr($other_name); ?>" class="tqs-airport-other" id="<?php echo esc_attr($id); ?>_other" placeholder="Enter airport name, city or IATA code" value="<?php echo esc_attr($other_val); ?>" style="<?php echo $show_other?'display:block;':'display:none;'; ?>" <?php echo ($show_other&&$required)?'required':''; ?> />
    </div>
    <?php
}

// ============================================================
// SEND EMAIL
// ============================================================
function tqs_send_inquiry_email(
    $full_name, $email, $full_phone, $full_whatsapp, $travel_type,
    $from, $from_other, $destination, $dest_other,
    $return_from, $return_from_other, $return_dest, $return_dest_other,
    $travel_date, $return_dep, $return_arr, $mc_legs,
    $adults, $kids, $infants, $trip_type, $cabin, $budget, $services,
    $quick_requests, $message
) {
    // FIX: removed duplicate $to=$to= assignment
    $to = get_option( 'tqs_admin_email', TQS_INQUIRY_DEFAULT_EMAIL );
    $total=$adults+$kids+$infants;
    $labels=['oneway'=>'One Way','return'=>'Return','multicity'=>'Multi-City'];
    $type_label=$labels[$travel_type]??ucfirst($travel_type);
    $subject='New '.$type_label.' Travel Inquiry - '.$full_name.' | '.$full_phone.' - TQS Travels';
    $bc=['oneway'=>'#2980b9','return'=>'#27ae60','multicity'=>'#8e44ad'];
    $badge_color=$bc[$travel_type]??'#555';

    // Determine display values based on travel type
    if ( $travel_type === 'return' ) {
        $from_display = ($return_from==='other'||empty($return_from)) ? $return_from_other : $return_from;
        $dest_display = ($return_dest==='other'||empty($return_dest)) ? $return_dest_other : $return_dest;
    } else {
        $from_display = ($from==='other'||empty($from)) ? $from_other : $from;
        $dest_display = ($destination==='other'||empty($destination)) ? $dest_other : $destination;
    }

    $wa_display=($full_whatsapp!==$full_phone)?esc_html($full_whatsapp):esc_html($full_phone).' <em style="color:#27ae60;">(same as phone)</em>';

    $b ="<html><body style='font-family:Arial,sans-serif;color:#333;margin:0;padding:0;'>";
    $b.="<div style='max-width:640px;margin:auto;border:1px solid #ddd;border-radius:8px;overflow:hidden;'>";
    $b.="<div style='background:linear-gradient(135deg,#1a0a2e,#3b1060);padding:24px;text-align:center;'><h2 style='color:#f5d6ff;margin:0;font-size:1.5em;letter-spacing:.1em;text-transform:uppercase;'>TQS TRAVELS</h2><p style='color:#a87bc0;margin:6px 0 0;font-size:.93em;'>New Travel Inquiry - ".esc_html($type_label)."</p></div>";
    $b.="<div style='padding:24px;background:#fff;'>";
    $b.="<div style='text-align:center;margin-bottom:20px;'><span style='background:".esc_attr($badge_color).";color:#fff;padding:6px 22px;border-radius:20px;font-size:.93em;font-weight:bold;letter-spacing:.08em;text-transform:uppercase;'>".esc_html($type_label)."</span></div>";
    $b.=tqs_email_section('Personal Information',['Full Name'=>esc_html($full_name),'Email'=>esc_html($email),'Phone'=>esc_html($full_phone),'WhatsApp'=>$wa_display]);
    if(in_array($travel_type,['oneway','return'],true)){
        $r=['From'=>esc_html($from_display)?:'N/A','To'=>esc_html($dest_display),'Departure Date'=>esc_html($travel_date)];
        if($travel_type==='return'){$r['Return Departure Date']=esc_html($return_dep);$r['Return Date']=esc_html($return_arr);}
        $b.=tqs_email_section('Route Details',$r);
    }
    if($travel_type==='multicity'&&!empty($mc_legs)){
        $lh="<table style='width:100%;border-collapse:collapse;margin-top:8px;'><thead><tr style='background:#1a0a2e;color:#f5d6ff;'><th style='padding:8px 12px;text-align:left;'>Leg</th><th style='padding:8px 12px;text-align:left;'>Departure Airport</th><th style='padding:8px 12px;text-align:left;'>Arrival Airport</th><th style='padding:8px 12px;text-align:left;'>Departure Date</th><th style='padding:8px 12px;text-align:left;'>Arrival Date</th></tr></thead><tbody>";
        foreach($mc_legs as $i=>$leg){$bg=$i%2===0?'#f9f9f9':'#fff';$mf=($leg['from']==='other'||empty($leg['from']))?$leg['from_other']:$leg['from'];$mt=($leg['to']==='other'||empty($leg['to']))?$leg['to_other']:$leg['to'];$lh.="<tr style='background:{$bg};'><td style='padding:8px 12px;font-weight:bold;'>Leg ".($i+1)."</td><td style='padding:8px 12px;'>".esc_html($mf)."</td><td style='padding:8px 12px;'>".esc_html($mt)."</td><td style='padding:8px 12px;'>".esc_html($leg['dep_date'])."</td><td style='padding:8px 12px;'>".esc_html($leg['arr_date'])."</td></tr>";}
        $lh.="</tbody></table>";
        $b.="<div style='margin-bottom:20px;'><h4 style='color:#1a0a2e;margin:0 0 8px;font-size:1em;border-bottom:2px solid #c724b1;padding-bottom:6px;letter-spacing:.06em;text-transform:uppercase;'>Multi-City Legs</h4>{$lh}</div>";
    }
    $ph="<table style='width:100%;border-collapse:collapse;margin-top:8px;'><thead><tr style='background:#1a0a2e;color:#f5d6ff;'><th style='padding:10px 14px;text-align:left;'>Type</th><th style='padding:10px 14px;text-align:left;'>Age Group</th><th style='padding:10px 14px;text-align:center;'>Count</th></tr></thead><tbody>";
    foreach([['Adults','12+ years',$adults],['Children','2 - 11 years',$kids],['Infants','Under 2 years',$infants]] as $ri=>$row){$bg=$ri%2===0?'#f9f9f9':'#fff';$ph.="<tr style='background:{$bg};'><td style='padding:10px 14px;font-weight:bold;'>".esc_html($row[0])."</td><td style='padding:10px 14px;color:#666;'>".esc_html($row[1])."</td><td style='padding:10px 14px;text-align:center;font-size:1.1em;font-weight:bold;color:#1a0a2e;'>".intval($row[2])."</td></tr>";}
    $ph.="<tr style='background:#e8f4fd;border-top:2px solid #1a0a2e;'><td colspan='2' style='padding:10px 14px;font-weight:bold;color:#1a0a2e;'>Total Passengers</td><td style='padding:10px 14px;text-align:center;font-size:1.2em;font-weight:bold;color:#1a0a2e;'>".intval($total)."</td></tr></tbody></table>";
    $b.="<div style='margin-bottom:20px;'><h4 style='color:#1a0a2e;margin:0 0 8px;font-size:1em;border-bottom:2px solid #c724b1;padding-bottom:6px;letter-spacing:.06em;text-transform:uppercase;'>Passengers</h4>{$ph}</div>";
    $sl=!empty($services)?implode(', ',array_map('esc_html',$services)):'None specified';
    $b.=tqs_email_section('Trip Preferences',['Trip Category'=>esc_html($trip_type)?:'N/A','Cabin Class'=>esc_html($cabin)?:'N/A','Budget (per person)'=>esc_html($budget)?:'N/A','Services Needed'=>$sl]);
    if(!empty($quick_requests)||!empty($message)){
        $b.="<div style='margin-bottom:20px;'><h4 style='color:#1a0a2e;margin:0 0 10px;font-size:1em;border-bottom:2px solid #c724b1;padding-bottom:6px;letter-spacing:.06em;text-transform:uppercase;'>Special Requests and Comments</h4>";
        if(!empty($quick_requests)){$b.="<div style='margin-bottom:12px;'><p style='margin:0 0 8px;font-weight:bold;color:#444;font-size:.88em;text-transform:uppercase;letter-spacing:.05em;'>Quick Requests:</p><div style='display:flex;flex-wrap:wrap;gap:8px;'>";foreach($quick_requests as $qr){$b.="<span style='background:#e8f4fd;color:#1a0a2e;padding:5px 12px;border-radius:20px;font-size:.88em;font-weight:600;border:1px solid #b3d4f0;'>".esc_html($qr)."</span>";}$b.="</div></div>";}
        if(!empty($message)){$b.="<div style='background:#f0f7ff;border-left:4px solid #c724b1;border-radius:0 6px 6px 0;padding:14px 16px;'><p style='margin:0 0 4px;font-weight:bold;color:#1a0a2e;font-size:.88em;text-transform:uppercase;letter-spacing:.05em;'>Comments:</p><p style='margin:0;line-height:1.6;color:#333;'>".nl2br(esc_html($message))."</p></div>";}
        $b.="</div>";
    }
    $b.="</div><div style='background:#1a0a2e;padding:14px;text-align:center;font-size:12px;color:#a87bc0;letter-spacing:.05em;'>Submitted via TQS Travels website inquiry form</div></div></body></html>";
    $headers=['Content-Type: text/html; charset=UTF-8','Reply-To: '.$full_name.' <'.$email.'>'];
    return wp_mail($to,$subject,$b,$headers);
}

function tqs_email_section($title,$rows){
    $h="<div style='margin-bottom:20px;'><h4 style='color:#1a0a2e;margin:0 0 8px;font-size:1em;border-bottom:2px solid #c724b1;padding-bottom:6px;letter-spacing:.06em;text-transform:uppercase;'>".esc_html($title)."</h4><table style='width:100%;border-collapse:collapse;'>";
    $i=0;foreach($rows as $l=>$v){$bg=$i%2===0?'#f9f9f9':'#fff';$h.="<tr style='background:{$bg};'><td style='padding:9px 13px;font-weight:bold;width:42%;color:#444;'>".esc_html($l)."</td><td style='padding:9px 13px;color:#222;'>{$v}</td></tr>";$i++;}
    $h.="</table></div>";return $h;
}

// ============================================================
// SETTINGS PAGE
// ============================================================
add_action('admin_menu','tqs_add_settings_page');
function tqs_add_settings_page(){add_options_page('TQS Travels Settings','TQS Travels','manage_options','tqs-travels-settings','tqs_render_settings_page');}
add_action('admin_init','tqs_register_settings');
function tqs_register_settings(){register_setting('tqs_settings_group','tqs_admin_email',['sanitize_callback'=>'sanitize_email']);}
function tqs_render_settings_page(){ ?>
<div class="wrap"><h1>TQS Travels - Inquiry Form Settings</h1>
<form method="post" action="options.php"><?php settings_fields('tqs_settings_group'); ?>
<table class="form-table"><tr><th scope="row"><label for="tqs_admin_email">Inquiry Recipient Email</label></th>
<td><input type="email" id="tqs_admin_email" name="tqs_admin_email" value="<?php echo esc_attr(get_option('tqs_admin_email',get_option('admin_email'))); ?>" class="regular-text" />
<p class="description">All form submissions will be sent to this address.</p></td></tr></table>
<?php submit_button(); ?></form><hr>
<h2>How to use</h2><p>Add the shortcode <code>[tqs_inquiry_form]</code> to any page or post.</p></div>
<?php }

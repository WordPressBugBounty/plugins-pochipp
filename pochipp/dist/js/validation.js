(()=>{window.addEventListener("load",(function(){const d=document.querySelector(".pchpp-setting__div.amazon-search"),c=document.querySelector(".pchpp-setting__div.amazon-affiliate"),r=document.querySelector(".pchpp-setting__div.rakuten-appid"),o=document.querySelector(".pchpp-setting__div.rakuten-affiliate"),p=document.querySelector(".pchpp-setting__div.yahoo-appid"),u=document.querySelector(".pchpp-setting__div.yahoo-affiliate"),s=document.querySelector(".pchpp-setting__div.moshimo"),l=document.querySelector(".pchpp-setting__div.mercari");d&&c&&e(),r&&o&&t(),p&&u&&n(),s&&a(),l&&i()}));const e=()=>{Object.values({accessKey:".pchpp-setting__div.amazon-search dl:nth-child(1) input",secretKey:".pchpp-setting__div.amazon-search dl:nth-child(2) input",affiliate:".pchpp-setting__div.amazon-affiliate dd input"}).forEach((e=>{document.querySelector(e).addEventListener("input",(e=>{const t=e.target.value,n=[c(t)].find((e=>""!==e))||"";d(e.target.closest("dd"),n)}))}))},t=()=>{Object.values({appId:".pchpp-setting__div.rakuten-appid",affiliate:".pchpp-setting__div.rakuten-affiliate"}).forEach((e=>{document.querySelector(e).addEventListener("input",(e=>{const t=e.target.value,n=[c(t)].find((e=>""!==e))||"";d(e.target.closest("dd"),n)}))}))},n=()=>{document.querySelector(".pchpp-setting__div.yahoo-appid").addEventListener("input",(e=>{const t=e.target.value,n=[c(t)].find((e=>""!==e))||"";d(e.target.closest("dd"),n)})),document.querySelector("#yahoo_linkswitch").addEventListener("input",(e=>{e.target.value=e.target.value.replace(/[^0-9]/g,"")}))},a=()=>{Object.values({amazon:".pchpp-setting__dl.-amazon > dd input",rakuten:".pchpp-setting__dl.-rakuten > dd input",yahoo:".pchpp-setting__dl.-yahoo > dd input"}).forEach((e=>{document.querySelector(e).addEventListener("input",(e=>{const t=e.target.value,n=[o(t,7),p(t,"number")].find((e=>""!==e))||"";d(e.target.closest("dd"),n)}))}))},i=()=>{document.querySelector("#mercari_ambassador_id").addEventListener("input",(e=>{const t=e.target.value,n=[p(t,"number"),r(t,10),o(t,10)].find((e=>""!==e))||"";d(e.target.closest("dd"),n)}))},d=(e,t)=>{const n=""!==t?"#d63638":"";e.querySelector("input").style.borderColor=n,e.querySelector(".errMessage").textContent=t},c=e=>e.includes(" ")||e.includes("　")?"不要なスペースが含まれています":"",r=(e,t)=>e.length<t?`入力可能な文字数は${t}文字以上です。`:"",o=(e,t)=>e.length>t?`入力可能な文字数は${t}文字までです。`:"",p=(e,t)=>{if("number"===t){const t=e.match(/^[0-9]*$/);return null===t||e!==t[0]?"入力可能な文字は数値のみです。":""}return""}})();
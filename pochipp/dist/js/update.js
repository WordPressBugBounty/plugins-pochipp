(()=>{window.addEventListener("load",(function(){window.fetch&&a()}));const a=async()=>{const a=document.querySelectorAll(".pochipp-box[data-auto-update]");if(0===a.length)return;const{ajaxUrl:e,ajaxNonce:n}=window.pchppVars,t=new URLSearchParams;t.append("action","auto_update"),t.append("nonce",n),t.append("pids",Array.from(a).map((a=>a.getAttribute("data-id"))).join(",")),await fetch(e,{method:"POST",cache:"no-cache",body:t}).then((a=>{if(a.ok)return a.json();throw new TypeError("Failed ajax!")}))}})();
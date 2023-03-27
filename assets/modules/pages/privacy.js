/* eslint-disable func-names */
/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable no-unused-vars */
/* eslint-disable no-param-reassign */
/* eslint-disable no-undef */
document.addEventListener("DOMContentLoaded", _event => {
    function setOptOutText(element) {
        _paq.push([
            function () {
                element.checked = !this.isUserOptedOut();
                if (this.isUserOptedOut()) {
                    document.querySelector("label#label-optedIn").style.display = "none";
                    document.querySelector("label#label-optedOut").style.display = "inline";
                } else {
                    document.querySelector("label#label-optedIn").style.display = "inline";
                    document.querySelector("label#label-optedOut").style.display = "none";
                }
            },
        ]);
    }

    const optOut = document.getElementById("optout");
    optOut.addEventListener("click", function () {
        if (this.checked) {
            _paq.push(["forgetUserOptOut"]);
        } else {
            _paq.push(["optUserOut"]);
        }
        setOptOutText(optOut);
    });
    setOptOutText(optOut);
});

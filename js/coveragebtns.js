(function ($) {
    function coverageBtns(controlDiv, gponAreas, wimaxBases, lteBases, fiberAreas, lteAreas) {
        this.gponAreas = gponAreas;
        this.wimaxBases = wimaxBases;
        this.fiberAreas = fiberAreas;
        this.lteBases = lteBases;
        this.lteAreas = lteAreas;
        
        //all techs
        // Set CSS for the control border    
        this.allBtnCnt = document.createElement('div');
        this.allBtnCnt.id = "allTechsBtn";
        this.allBtnCnt.style.backgroundColor = '#fff';
        this.allBtnCnt.style.border = '1px solid silver';
        this.allBtnCnt.style.cursor = 'pointer';
        this.allBtnCnt.style.marginTop = '10px';
        this.allBtnCnt.style.textAlign = 'center';
        this.allBtnCnt.title = 'Click to toggle coverage diplay';
        controlDiv.appendChild(this.allBtnCnt);
        // Set CSS for the control interior
        this.allText = document.createElement('div');
        this.allText.id = "divToggleAll";
        this.allText.style.color = 'rgb(25,25,25)';
        this.allText.style.fontFamily = 'Roboto,Arial,sans-serif';
        this.allText.style.fontSize = '12px';
        this.allText.style.lineHeight = '26px';
        this.allText.style.paddingLeft = '5px';
        this.allText.style.paddingRight = '5px';
        this.allText.innerHTML = 'Show Coverage (All)';
        this.allBtnCnt.appendChild(this.allText);
        
        //Gpon tech    
        this.gponBtnCnt = document.createElement('div');
        this.gponBtnCnt.id = "gponTechBtn";
        this.gponBtnCnt.style.backgroundColor = '#fff';
        this.gponBtnCnt.style.border = '1px solid silver';
        this.gponBtnCnt.style.cursor = 'pointer';
        this.gponBtnCnt.style.marginTop = '10px';
        this.gponBtnCnt.style.textAlign = 'center';
        this.gponBtnCnt.title = 'Click to toggle GPON coverage diplay';
        controlDiv.appendChild(this.gponBtnCnt);
        // Set CSS for the control interior
        this.gponText = document.createElement('div');
        this.gponText.id = "divToggleGpon";
        this.gponText.style.color = 'rgb(25,25,25)';
        this.gponText.style.fontFamily = 'Roboto,Arial,sans-serif';
        this.gponText.style.fontSize = '12px';
        this.gponText.style.lineHeight = '26px';
        this.gponText.style.paddingLeft = '5px';
        this.gponText.style.paddingRight = '5px';
        this.gponText.innerHTML = 'Show GPON Coverage';
        this.gponBtnCnt.appendChild(this.gponText);
        
        //wimax tech    
        this.wimaxBtnCnt = document.createElement('div');
        this.wimaxBtnCnt.id = "wimaxTechBtn";
        this.wimaxBtnCnt.style.backgroundColor = '#fff';
        this.wimaxBtnCnt.style.border = '1px solid silver';
        this.wimaxBtnCnt.style.cursor = 'pointer';
        this.wimaxBtnCnt.style.marginTop = '10px';
        this.wimaxBtnCnt.style.textAlign = 'center';
        this.wimaxBtnCnt.title = 'Click to toggle WiMax coverage diplay';
        controlDiv.appendChild(this.wimaxBtnCnt);
        // Set CSS for the control interior
        this.wimaxText = document.createElement('div');
        this.wimaxText.id = "divToggleWimax";
        this.wimaxText.style.color = 'rgb(25,25,25)';
        this.wimaxText.style.fontFamily = 'Roboto,Arial,sans-serif';
        this.wimaxText.style.fontSize = '12px';
        this.wimaxText.style.lineHeight = '26px';
        this.wimaxText.style.paddingLeft = '5px';
        this.wimaxText.style.paddingRight = '5px';
        this.wimaxText.innerHTML = 'Show WiMax Coverage';
        this.wimaxBtnCnt.appendChild(this.wimaxText);
        
        //fiber tech    
        this.fiberBtnCnt = document.createElement('div');
        this.fiberBtnCnt.id = "fiberTechBtn";
        this.fiberBtnCnt.style.backgroundColor = '#fff';
        this.fiberBtnCnt.style.border = '1px solid silver';
        this.fiberBtnCnt.style.cursor = 'pointer';
        this.fiberBtnCnt.style.marginTop = '10px';
        this.fiberBtnCnt.style.textAlign = 'center';
        this.fiberBtnCnt.title = 'Click to toggle Fiber coverage diplay';
        controlDiv.appendChild(this.fiberBtnCnt);
        // Set CSS for the control interior
        this.fiberText = document.createElement('div');
        this.fiberText.id = "divToggleFiber";
        this.fiberText.style.color = 'rgb(25,25,25)';
        this.fiberText.style.fontFamily = 'Roboto,Arial,sans-serif';
        this.fiberText.style.fontSize = '12px';
        this.fiberText.style.lineHeight = '26px';
        this.fiberText.style.paddingLeft = '5px';
        this.fiberText.style.paddingRight = '5px';
        this.fiberText.innerHTML = 'Show Fiber Coverage';
        this.fiberBtnCnt.appendChild(this.fiberText);
        
        //LTE tech    
        this.lteBtnCnt = document.createElement('div');
        this.lteBtnCnt.id = "lteTechBtn";
        this.lteBtnCnt.style.backgroundColor = '#fff';
        this.lteBtnCnt.style.border = '1px solid silver';
        this.lteBtnCnt.style.cursor = 'pointer';
        this.lteBtnCnt.style.marginTop = '10px';
        this.lteBtnCnt.style.textAlign = 'center';
        this.lteBtnCnt.title = 'Click to toggle WiBroniks base stations diplay';
        controlDiv.appendChild(this.lteBtnCnt);
        // Set CSS for the control interior
        this.lteText = document.createElement('div');
        this.lteText.id = "divToggleLTE";
        this.lteText.style.color = 'rgb(25,25,25)';
        this.lteText.style.fontFamily = 'Roboto,Arial,sans-serif';
        this.lteText.style.fontSize = '12px';
        this.lteText.style.lineHeight = '26px';
        this.lteText.style.paddingLeft = '5px';
        this.lteText.style.paddingRight = '5px';
        this.lteText.innerHTML = 'Show WiBroniks Base Station';
        this.lteBtnCnt.appendChild(this.lteText);
        
        //lte area tech    
        this.lteAreasBtnCnt = document.createElement('div');
        this.lteAreasBtnCnt.id = "lteAreasTechBtn";
        this.lteAreasBtnCnt.style.backgroundColor = '#fff';
        this.lteAreasBtnCnt.style.border = '1px solid silver';
        this.lteAreasBtnCnt.style.cursor = 'pointer';
        this.lteAreasBtnCnt.style.marginTop = '10px';
        this.lteAreasBtnCnt.style.textAlign = 'center';
        this.lteAreasBtnCnt.title = 'Click to toggle WiBroniks coverage diplay';
        controlDiv.appendChild(this.lteAreasBtnCnt);
        // Set CSS for the control interior
        this.lteAreasText = document.createElement('div');
        this.lteAreasText.id = "divToggleLteAreas";
        this.lteAreasText.style.color = 'rgb(25,25,25)';
        this.lteAreasText.style.fontFamily = 'Roboto,Arial,sans-serif';
        this.lteAreasText.style.fontSize = '12px';
        this.lteAreasText.style.lineHeight = '26px';
        this.lteAreasText.style.paddingLeft = '5px';
        this.lteAreasText.style.paddingRight = '5px';
        this.lteAreasText.innerHTML = 'Show WiBroniks Coverage';
        this.lteAreasBtnCnt.appendChild(this.lteAreasText);
    }
    coverageBtns.prototype.showCoverage = function (btn, visibility) {
        this.reset();
        // Try GPON   
        if (visibility) $('#' + btn.id + ' > div').addClass('active');
        if (btn === this.gponBtnCnt) {
            for (var x in this.gponAreas) {
                this.gponAreas[x].shape.setVisible(visibility);
            }
            for (var x in this.wimaxBases) {
                this.wimaxBases[x].shape.setVisible(false);
            }
            for (var x in this.fiberAreas) {
                this.fiberAreas[x].shape.setVisible(false);
            }
            for (var x in this.lteBases) {
                this.lteBases[x].shape.setVisible(false);
            }
            for (var x in this.lteAreas) {
                this.lteAreas[x].shape.setVisible(false);
            }
            //change text
            if (visibility) this.gponText.innerHTML = 'Hide GPON Coverage';
        }
        else if (btn === this.wimaxBtnCnt) {
            // Try Wimax
            for (var x in this.wimaxBases) {
                this.wimaxBases[x].shape.setVisible(visibility);
            }
            for (var x in this.fiberAreas) {
                this.fiberAreas[x].shape.setVisible(false);
            }
            for (var x in this.gponAreas) {
                this.gponAreas[x].shape.setVisible(false);
            }
            for (var x in this.lteBases) {
                this.lteBases[x].shape.setVisible(false);
            }
            for (var x in this.lteAreas) {
                this.lteAreas[x].shape.setVisible(false);
            }
            if (visibility) this.wimaxText.innerHTML = 'Hide WiMax Coverage';
        }
        else if (btn === this.fiberBtnCnt) {
            // Try fiber
            for (var x in this.fiberAreas) {
                this.fiberAreas[x].shape.setVisible(visibility);
            }
            for (var x in this.wimaxBases) {
                this.wimaxBases[x].shape.setVisible(false);
            }
            for (var x in this.gponAreas) {
                this.gponAreas[x].shape.setVisible(false);
            }
            for (var x in this.lteBases) {
                this.lteBases[x].shape.setVisible(false);
            }
            for (var x in this.lteAreas) {
                this.lteAreas[x].shape.setVisible(false);
            }
            if (visibility) this.fiberText.innerHTML = 'Hide Fiber Coverage';
        }
        else if (btn === this.lteBtnCnt) {
            // Try LTE
            for (var x in this.lteBases) {
                this.lteBases[x].shape.setVisible(visibility);
                //this.lteAreas[x].shape.setVisible(visibility);
            }
            for (var x in this.wimaxBases) {
                this.wimaxBases[x].shape.setVisible(false);
            }
            for (var x in this.fiberAreas) {
                this.fiberAreas[x].shape.setVisible(false);
            }
            for (var x in this.gponAreas) {
                this.gponAreas[x].shape.setVisible(false);
            }
            for (var x in this.lteAreas) {
                this.lteAreas[x].shape.setVisible(false);
            }
            if (visibility) this.lteText.innerHTML = 'Hide WiBroniks Base Station';
        }
        else if (btn === this.lteAreasBtnCnt) {
            // Try Lte Areas
            for (var x in this.lteAreas) {
                this.lteAreas[x].shape.setVisible(visibility);
            }
            for (var x in this.fiberAreas) {
                this.fiberAreas[x].shape.setVisible(false);
            }
            for (var x in this.wimaxBases) {
                this.wimaxBases[x].shape.setVisible(false);
            }
            for (var x in this.gponAreas) {
                this.gponAreas[x].shape.setVisible(false);
            }
            for (var x in this.lteBases) {
                this.lteBases[x].shape.setVisible(false);
            }
            if (visibility) this.lteAreasText.innerHTML = 'Hide WiBroniks Coverage';
        }
        else if (btn === this.allBtnCnt) {
            for (var x in this.gponAreas) {
                this.gponAreas[x].shape.setVisible(visibility);
            }
            // Try Fibre
            for (var x in this.fiberAreas) {
                this.fiberAreas[x].shape.setVisible(visibility);
            }
            // Try Wimax
            for (var x in this.wimaxBases) {
                this.wimaxBases[x].shape.setVisible(visibility);
            }
            // Try LTE
            for (var x in this.lteBases) {
                this.lteBases[x].shape.setVisible(visibility);
            }
            // Try Lte Areas
            for (var x in this.lteAreas) {
                this.lteAreas[x].shape.setVisible(visibility);
            }
            if (visibility) this.allText.innerHTML = 'Hide Coverage (All)';
        }
    }
    coverageBtns.prototype.reset = function () {
        $('#coverageBtns > div > div').removeClass('active');
        this.fiberText.innerHTML = 'Show Fiber Coverage';
        this.gponText.innerHTML = 'Show GPON Coverage';
        this.allText.innerHTML = 'Show Coverage (All)';
        this.wimaxText.innerHTML = 'Show WiMax Coverage';
        this.lteText.innerHTML = 'Show WiBroniks Base Station';
        this.lteAreasText.innerHTML = 'Show WiBroniks Coverage';
    }
    window.CoverageButtons = window.CoverageButtons || coverageBtns;
})(jQuery);
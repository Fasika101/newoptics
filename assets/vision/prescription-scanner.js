/**
 * Prescription scanner: sends the uploaded prescription photo to
 * scan-prescription.php (Google Gemini) and auto-fills the vision dropdowns.
 * The customer must always review the values before adding to cart.
 */
(function () {

    var scanBtn, statusBox;

    document.addEventListener('DOMContentLoaded', function () {
        scanBtn = document.getElementById('scan-prescription-btn');
        statusBox = document.getElementById('scan-prescription-status');
        if (scanBtn) {
            scanBtn.addEventListener('click', scanPrescription);
        }
    });

    function setStatus(html, color) {
        if (statusBox) {
            statusBox.style.color = color || '#333';
            statusBox.innerHTML = html;
        }
    }

    /**
     * Select the option whose visible text matches the wanted numeric value.
     * Dispatches a 'change' event so the existing price-calculation
     * onchange handlers run.
     */
    function fillSelect(selectId, value, tolerance) {
        if (value === null || value === undefined || value === '') return false;
        var select = document.getElementById(selectId);
        if (!select) return false;

        var target = parseFloat(value);
        if (isNaN(target)) return false;
        tolerance = tolerance || 0.01;

        var bestIndex = -1;
        var bestDiff = Infinity;
        for (var i = 0; i < select.options.length; i++) {
            var text = select.options[i].text.trim();
            if (text === '' || text === '-' || text === 'Choose One') continue;

            var num = /plano/i.test(text) ? 0 : parseFloat(text);
            if (isNaN(num)) continue;

            var diff = Math.abs(num - target);
            if (diff < bestDiff) {
                bestDiff = diff;
                bestIndex = i;
            }
        }

        if (bestIndex >= 0 && bestDiff <= tolerance) {
            select.selectedIndex = bestIndex;
            select.dispatchEvent(new Event('change'));
            return true;
        }
        return false;
    }

    /** Open a panel by triggering the site's existing jQuery-bound buttons. */
    function clickPanelButton(selector) {
        if (window.jQuery && jQuery(selector).length) {
            jQuery(selector).first().trigger('click');
        }
    }

    function scanPrescription() {
        var fileInput = document.getElementById('formFile');
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            setStatus('Please choose a prescription photo first.', '#c00');
            return;
        }

        var formData = new FormData();
        formData.append('prescr_photo', fileInput.files[0]);

        scanBtn.disabled = true;
        setStatus('Scanning your prescription, please wait&hellip;', '#555');

        fetch('scan-prescription.php', { method: 'POST', body: formData })
            .then(function (res) { return res.json(); })
            .then(function (json) {
                scanBtn.disabled = false;
                if (!json.success) {
                    setStatus('Scan failed: ' + json.error, '#c00');
                    return;
                }
                applyResult(json.data);
            })
            .catch(function () {
                scanBtn.disabled = false;
                setStatus('Scan failed: could not contact the server. Please fill the values manually.', '#c00');
            });
    }

    function applyResult(data) {
        var right = data.right_eye || {};
        var left = data.left_eye || {};
        var pd = data.pd || {};

        // Progressive if the model says so, or if any ADD value was found
        var isProgressive = data.vision_type === 'progressive' || !!(right.add || left.add);

        var filled = [];
        var failed = [];

        function apply(label, selectId, value, tolerance) {
            if (value === null || value === undefined || value === '') return;
            if (fillSelect(selectId, value, tolerance)) {
                filled.push(label);
            } else {
                failed.push(label + ' (' + value + ')');
            }
        }

        if (isProgressive) {
            // Open the Progressive Vision panel
            clickPanelButton('.Single[target="2"]');

            apply('Right SPH', 'psph-right', right.sph);
            apply('Right CYL', 'pcyl-right', right.cyl);
            apply('Right Axis', 'paxis-right', right.axis);
            apply('Right ADD', 'padd-right', right.add);
            apply('Left SPH', 'psph-left', left.sph);
            apply('Left CYL', 'pcyl-left', left.cyl);
            apply('Left Axis', 'paxis-left', left.axis);
            apply('Left ADD', 'padd-left', left.add);

            if (pd.type === 'one' && pd.one !== null && pd.one !== undefined) {
                clickPanelButton('.progressive_pd[prog_pd_target="5"]');
                apply('PD', 'p_one_pd', pd.one, 0.5);
            } else if (pd.type === 'two') {
                clickPanelButton('.progressive_pd[prog_pd_target="6"]');
                apply('PD Right', 'p_two_pd_right', pd.right, 0.26);
                apply('PD Left', 'p_two_pd_left', pd.left, 0.26);
            }
        } else {
            // Open the Single Vision panel
            clickPanelButton('.Single[target="1"]');

            apply('Right SPH', 'ssph-right', right.sph);
            apply('Right CYL', 'scyl-right', right.cyl);
            apply('Right Axis', 'saxis-right', right.axis);
            apply('Left SPH', 'ssph-left', left.sph);
            apply('Left CYL', 'scyl-left', left.cyl);
            apply('Left Axis', 'saxis-left', left.axis);

            if (pd.type === 'one' && pd.one !== null && pd.one !== undefined) {
                clickPanelButton('.pd[pdtarget="3"]');
                apply('PD', 's_one_pd', pd.one, 0.5);
            } else if (pd.type === 'two') {
                clickPanelButton('.pd[pdtarget="4"]');
                apply('PD Right', 's_two_pd_right', pd.right, 0.26);
                apply('PD Left', 's_two_pd_left', pd.left, 0.26);
            }
        }

        // Build the status message
        var typeLabel = isProgressive ? 'Progressive Vision' : 'Single Vision';
        var html = '<b>Scan complete</b> (' + typeLabel + ', confidence: ' + (data.confidence || 'unknown') + ').<br>';
        if (filled.length) {
            html += 'Auto-filled: ' + filled.join(', ') + '.<br>';
        }
        if (failed.length) {
            html += '<span style="color:#c00;">Could not match, please select manually: ' + failed.join(', ') + '.</span><br>';
        }
        if (data.notes) {
            html += '<span style="color:#a60;">Note: ' + data.notes + '</span><br>';
        }
        html += '<b style="color:#c00;">Please verify every value against your prescription before adding to cart.</b>';

        var color = (data.confidence === 'low' || failed.length) ? '#a60' : '#060';
        setStatus(html, color);
    }

})();

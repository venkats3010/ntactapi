var dtoken = $('meta[name="csrf-token"]').attr('content');


function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
  return true;
}

$(document).ready(function() {
    // Toggle the dropdown menu on click
    $('.multiSelect-dropdown-toggle').click(function() {
        $('.multiSelect-dropdown-menu').toggle();
    });

    // Close the dropdown if clicked outside
    $(document).click(function(e) {
        if (!$(e.target).closest('.multiSelect-dropdown').length) {
            $('.multiSelect-dropdown-menu').hide();
        }
    });

    // Update the toggle display based on selected options
    $('.multiSelect-dropdown-menu input[type="checkbox"]').change(function() {
        const selectedOptions = [];
        $('.multiSelect-dropdown-menu input[type="checkbox"]:checked').each(function() {
            selectedOptions.push($(this).parent().text().trim()); // Get the label text
        });

        // Clear the toggle and display chips
        $('.multiSelect-dropdown-toggle').empty();
        if (selectedOptions.length) {
            selectedOptions.forEach(option => {
                const chip = $('<span class="multiSelect-chip"></span>').text(option);
                const remove = $('<span class="remove">&times;</span>').click(function() {
                    const checkbox = $('.multiSelect-dropdown-menu input[type="checkbox"]').filter(function() {
                        return $(this).parent().text().trim() === option;
                    });
                    checkbox.prop('checked', false).change(); // Uncheck the checkbox
                    $(this).parent().remove(); // Remove the chip
                });
                chip.append(remove);
                $('.multiSelect-dropdown-toggle').append(chip);
            });
        } else {
            $('.multiSelect-dropdown-toggle').text('Select options');
        }
    });
});
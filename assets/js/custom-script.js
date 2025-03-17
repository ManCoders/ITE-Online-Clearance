jQuery(function ($) {
    var base_url = $('body').attr('data-base-url');

    $('body').on('click', '.login-toggle', function () {
        var $this = $(this);
        $('.login-toggle').removeClass('active');
        $this.addClass('active');
    });


    $('#loginForm').on('submit', function (e) {
        e.preventDefault();

        const type = $('.login-toggle.active').attr('data-login-type') ?? 'admin';
        const email = $('#loginForm [type=email]').val();
        const password = $('#loginForm [type=password]').val();

        $('#loginForm [type=submit]').attr('disabled', true);
        $('#loginForm [type=submit] .spinner-border').removeClass('d-none');
        $('.alert_message_mod').html();
        $.ajax({
            url: base_url + 'api/login',
            type: 'POST',
            data: {
                action: 'user-login',
                type: type,
                email: email,
                password: password
            },
            success: function (res) {
                if (res == 1) {
                    $('.alert_message_mod').html('<div class="alert alert-success" role="alert">Logging in, please wait.</div>');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                }
                if (res == 2) {
                    $('.alert_message_mod').html('<div class="alert alert-danger" role="alert">Password is incorrect.</div>');
                }

                if (res == 0) {
                    $('.alert_message_mod').html('<div class="alert alert-danger" role="alert">User not found in the system.</div>');
                }
                $('#loginForm [type=submit]').attr('disabled', false);
                $('#loginForm [type=submit] .spinner-border').addClass('d-none');
            },
            error: function (error) {
                $('.alert_message_mod').html('<div class="alert alert-danger" role="alert">Something went wrong please try again.</div>');
                $('#loginForm [type=submit]').attr('disabled', false);
                $('#loginForm [type=submit] .spinner-border').addClass('d-none');
            }
        });
    })

    if ($('.curriculum-select').length) {
        $('.curriculum-select').select2({
            ajax: {
                url: base_url + 'api/get/curriculum-list', // Server URL to fetch data
                dataType: 'json', // Expected response format
                delay: 250, // Add a delay to reduce the frequency of AJAX requests
                processResults: function (data) {
                    if (!Array.isArray(data)) {
                        data = Object.values(data);
                    }
                    return {
                        results: data[0].map(function (item) {
                            return {
                                id: item.id, // This should be the ID of the curriculum item
                                text: item.curriculum_name + ' ' + item.course + ' ' + item.school_year + ' ' + item.semester + ' semester' // This should be the display name of the curriculum item
                            };
                        })
                    };
                },
                cache: true, // Enable caching to reduce server load
            },
            placeholder: 'Select a curriculum(s)', // Placeholder text when no option is selected
            dropdownAutoWidth: true, // Automatically adjust dropdown width to fit its contents
            width: '100%', // Set width to 100% for the container
            multiple: true // Enable multiple selection
        });
        if ($('.subject-select').length) {
            $('.subject-select').select2({
                placeholder: 'Select subject(s)',
                width: '100%',
                multiple: true
            });
        }
        $('.curriculum-select').on('change', function () {
            var selectedCurricula = $(this).val(); // Get selected values from the first Select2

            // Clear the second Select2 options before updating
            $('.subject-select').empty();

            // If there are selected curricula, fetch related options from the database
            if (selectedCurricula.length > 0) {
                // AJAX call to fetch related options
                $.ajax({
                    url: base_url + 'api/get/subject-by-curricula', // The endpoint to handle the request
                    type: 'GET',
                    data: {
                        selectedCurricula: selectedCurricula
                    }, // Send the selected curricula to the server
                    dataType: 'json',
                    success: function (response) {
                        if (response && response.data.length) {
                            // Populate the second Select2 with the fetched options
                            let data = response.data;
                            if (!Array.isArray(data)) {
                                data = Object.values(data);
                            }
                            data.forEach(function (item) {
                                var newOption = new Option(item.name, item.id, false, false); // Adjust 'name' and 'id' based on your data
                                if ($('.subject-select').length) {
                                    $('.subject-select').append(newOption);
                                }
                            });

                            // Trigger Select2 to refresh the second dropdown
                            $('.subject-select').trigger('change');
                        }
                    },
                    error: function () {}
                });
            }
        });
    }
    initSelect2ForSubjects()
    //Get all curriculum by course_id
    function initSelect2ForSubjects() {
        if ($('.enrollSubjectByStudent').length) {
            const user_id = $('.enrollSubjectByStudent [name=user_id]').val()

            $.ajax({
                url: base_url + 'api/get/curriculum-list',
                type: 'GET',
                data: {
                    student_id: user_id
                },
                success: function (result) {
                    const res = JSON.parse(result).data;

                    let data = [];
                    if (res.length > 0) {
                        for (var i = 0; i < res.length; i++) {
                            data.push(res[i].id);
                        }
                    }
                    console.log(data)
                    $('.student-subject-select').select2({
                        ajax: {
                            url: base_url + 'api/get/subject-by-student-curricula', // The endpoint to handle the request
                            data: function (params) {
                                return {
                                    selectedCurricula: data, // Pass the selected curricula dynamically
                                    q: params.term // Allow filtering based on input
                                };
                            },
                            dataType: 'json',
                            processResults: function (data) {
                                // Map the response data to Select2 format
                                return {
                                    results: data.data.map(function (item) {
                                        return {
                                            id: item.id, // Assuming 'id' is the identifier
                                            text: item.code + ' - '+ item.name + ' ' + item.school_year + ' ' + item.semester + ' semester (Prof. ' + item.teacher_name + ')', // Assuming 'name' is the display text
                                            data: {
                                                subject_id: item.subject_id,  // Example data attribute for teacher ID
                                                teacher_id: item.id  // Example data attribute for curriculum ID
                                            }
                                        };
                                    })
                                };
                            },
                            cache: true // Cache the results for faster performance
                        },
                        placeholder: 'Select a subject', // Placeholder text
                        allowClear: true, // Allow the user to clear the selection
                        multiple: true,
                    });
                }
            });
        }
    }
    
   
   $(document).on('submit', '.enrollSubjectByStudent', function (evt) {
    evt.preventDefault();
    var selectedTeacherIds = [];
    var selectedSubjectIds = [];
    const selectedOptions = $('.student-subject-select').select2('data');
    console.log(selectedOptions)
    // Assuming the teacher and curriculum are being selected via data attributes or options in the select
    selectedOptions.forEach(function (option) {
        console.log(option)
       selectedTeacherIds.push(option.data.teacher_id); // Get teacher ID from data attribute
       selectedSubjectIds.push(option.data.subject_id); // Get curriculum ID from data attribute
   });

    // Add selected IDs to FormData
    var formData = new FormData(this);
    formData.append('selected_teacher_ids', selectedTeacherIds.join(',')); // Append selected teacher IDs
    formData.append('selected_subject_ids', selectedSubjectIds.join(',')); // Append selected curriculum IDs
    formData.append('itemslength', selectedSubjectIds.length); // Append selected curriculum IDs

    $.ajax({
       url: base_url + 'api/enroll/student',
       type: "POST",
       data: formData,
       contentType: false,
       cache: false,
       processData: false,
       success: function (result) {
          //alert(result);
          if (result == 1) {
             $('.alert_message_mod').html('<div class="alert alert-success" role="alert">Enrolled to subject(s)!</div>');
            
             $('.student-subject-select').empty(); // Reset Select2 for subjects
          } else {
             $('.alert_message_mod').html('<div class="alert alert-danger" role="alert">Error enrolling student!!! Please try again later.</div>');
          }
       }


    });
 });
})
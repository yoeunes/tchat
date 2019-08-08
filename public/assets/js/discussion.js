var Discussion = function (options) {
    !options && $.error('options required');

    this.currentUser = options.currentUser;
    moment.locale('fr');
};

Discussion.prototype.bindEvents = function () {
    var self = this;

    self.loadActiveUsers(self.currentUser, self.loadActiveChat);
    self.loadActiveChat(self.currentUser);

    setInterval(function () {
        self.loadActiveUsers(self.currentUser, self.loadActiveChat);
        self.loadActiveChat(self.currentUser);
    }, 3000);

    $(document).on('click', '.usernameLink', function (event) {
        event.preventDefault();

        $('.chat_list').removeClass('active_chat');
        $(this).closest('.chat_list').addClass('active_chat');

        self.loadActiveChat(self.currentUser);
    });

    $('form.message_box_write').on('submit', function (event) {
        event.preventDefault();

        var otherUser = $('.active_chat').first().attr('data-otherUser');

        if ($('input[name="message"]').val().trim() !== "") {
            $.ajax({
                type: 'post',
                url: '/discussion/store',
                dataType: 'json',
                data: $(this).serialize() + '&otherUser=' + otherUser,
                success: function (response) {
                    if (response.status === 'ok') {
                        $('.msg_history').append('<div class="outgoing_msg">\n' +
                            '                    <div class="sent_msg">\n' +
                            '                        <p>' + $('input[name="message"]').val() + '</p>\n' +
                            '                        <span class="time_date"> ' + moment().format('LLL') + '</span></div>\n' +
                            '                </div>');
                        $('input[name="message"]').val('');
                    }
                }
            });
        }
    });
};

Discussion.prototype.loadActiveUsers = function (currentUser, loadActiveChat) {
    var self = this;
    $.ajax({
        type: 'get',
        url: '/user/getUsersList',
        dataType: 'json',
        success: function (response) {
            var selectedUser = $('.active_chat').first().attr('data-otherUser');

            $('.inbox_chat').html('');
            if (response.status === 'ok') {
                if(undefined === selectedUser && response.result.length > 0) {
                    selectedUser = response.result[0].id;
                }
                response.result.forEach(user => {
                    $('.inbox_chat').append('<div class="chat_list ' + (user.id == selectedUser ? 'active_chat' : '') + '" data-otherUser="' + user.id + '">\n' +
                        '                    <div class="chat_people">\n' +
                        '                        <div class="chat_img"><img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"></div>\n' +
                        '                        <a href="#" class="card-link chat_ib usernameLink">\n' +
                        '                            <h5>' + user.username + '</h5>\n' +
                        '                            <span class="badge badge-light not-read-count">'+(undefined !== user.notReadCount ? user.notReadCount : '')+'</span>' +
                        '                            <svg height="10" width="10" class="online-status"><circle cx="5" cy="5" r="5" fill="' + ('1' === user.online ? 'green' : 'red') + '" /></svg>' +
                        '                        </a>\n' +
                        '                    </div>\n' +
                        '                </div>');

                    selected = true;
                });

                loadActiveChat(currentUser);
            }
        }
    });
};

Discussion.prototype.loadActiveChat = function (currentUser) {
    if ($('.active_chat').length > 0) {
        var otherUser = $('.active_chat').first().attr('data-otherUser');
        $.ajax({
            type: 'get',
            url: '/discussion/discussion?otherUser=' + otherUser,
            dataType: 'json',
            success: function (response) {
                $('.msg_history').html('');
                if (response.status === 'ok') {
                    response.result.forEach(discution => {
                        if (parseInt(discution.from_user) !== parseInt(currentUser)) {
                            $('.msg_history').append('<div class="incoming_msg">\n' +
                                '                    <div class="incoming_msg_img"><img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"></div>\n' +
                                '                    <div class="received_msg">\n' +
                                '                        <div class="received_withd_msg">\n' +
                                '                            <p>' + discution.message + '</p>\n' +
                                '                            <span class="time_date"> ' + moment(discution.created_at).format('LLL') + '</span></div>\n' +
                                '                    </div>\n' +
                                '                </div>');
                        } else {
                            $('.msg_history').append('<div class="outgoing_msg">\n' +
                                '                    <div class="sent_msg">\n' +
                                '                        <p>' + discution.message + '</p>\n' +
                                '                        <span class="time_date"> ' + moment(discution.created_at).format('LLL') + '</span></div>\n' +
                                '                </div>');
                        }
                    });
                }
            }
        });
    }
};

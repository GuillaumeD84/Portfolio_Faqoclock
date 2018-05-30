var app = {
  init: function() {
    $('.addQuestionVoteBtn').on('click', app.addVote);
    $('.addAnswerVoteBtn').on('click', app.addVote);
  },
  addVote: function(evt) {
    $.ajax({
      url: evt.target.dataset.voteUrl,
      method: 'GET'
    })
      .done(function(result) {
        var voteValueBadge = $(evt.target).siblings('.badge');
        var voteValue = voteValueBadge.text();
        $(evt.target).siblings('.badge').text(parseInt(voteValue)+1);
        $(evt.target).siblings('.badge')
          .removeClass('badge-primary')
          .addClass('badge-success');

        $(evt.target).remove();
      });
  }
};

$(app.init);

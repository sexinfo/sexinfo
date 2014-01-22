	$(function(){
		$(".suggestions-container").html(
        h2("Suggested Articles") + ul(function(){
          return li("First article") + li ("Second article") + li ("Third article") + li ("Wow, it never ends!");
        })

      );
	});


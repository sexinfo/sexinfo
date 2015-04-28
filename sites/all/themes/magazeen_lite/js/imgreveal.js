// Add a class of 'img-reveal' to any <img> to automatically add toggle controls

$(function() {
    $("img.img-reveal").each(attachImageReveal);

    function attachImageReveal() {
        console.log("Hello World");
        var $image = $(this);
        $image.wrap("<div class='main_container'></div>");
        var $container = $image.parent();
        var $inner_container = $("<div class='inner_container'><p class='noselect' style='margin:1em;'>Click to Reveal Image</p></div>")
        $inner_container.css('opacity', '0');
        $inner_container.css('position', 'relative');
        $inner_container.css('top', '100px');
        $inner_container.css('display', 'table');
        $inner_container.css('border', '2px solid black');
        $inner_container.css('border-radius', '10px');
        $inner_container.css('padding', '6px');

        $image.before($inner_container);
        var control = false;
        
        var reveal = function() {
            console.log("Reveal");
            $image.animate({opacity: 1}, {duration: 500, queue: false})
            $inner_container.animate({opacity: 0}, {duration: 500, complete: toggle(), queue: false})
        }
        
        var obscure = function() {
            console.log("Obscure");
            $image.animate({opacity: 0}, {duration: 500, queue: false})
            $inner_container.animate({opacity: 1}, {duration: 500, complete: toggle(), queue: false})
        }

        var toggle = function() {
            var f = control ? reveal : obscure;
            var g = control ? obscure : reveal;
            
            console.log("Toggle");
            $container.unbind('click', f);
            $container.bind('click', g);
            
            control = !control;
        }
        
        obscure();
    }

});


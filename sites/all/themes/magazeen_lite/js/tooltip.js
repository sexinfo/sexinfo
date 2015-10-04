/*
 * Module for tooltip functions
 */
$(document).ready(function() {

    var Tooltip = Tooltip || (function() {

        /*
         * Reads questions.json file to retrieve words with definitions. For
         * each word in the PPQ quesiton, attach a class and tooltip listener.
         */
        function processQuestion(question) {
            questions = loadJSON("questions.json");
            var startKey = questions['start'];
            var currentQuestion = questions[startKey];
            var i = 0;
            var words = [];
            for (var key in tooltipsArray) {
            	words[i] = key;
            	i++;
            }
            console.log(words);

            words.forEach(function (word) {
                // 'g' is global flag
                question = question.replace(new RegExp(word), "<span id="+word+" class='tooltip'>"+word+"</span>");
            });
            return question;
        }

        /

    }())

}

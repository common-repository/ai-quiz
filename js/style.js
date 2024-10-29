console.log(aiQuiz);
var questions_arr = aiQuiz.questions;

function select_option(x) {
  var options = document.querySelectorAll(".ai-quiz-option");
  for (var i = 0; i < options.length; i++) {
    options[i].classList.remove("selected");
  }
  x.classList.toggle("selected");
  document.getElementById("ai-quiz-checkbutton").style.display = "block";
}



jQuery(document).ready(function ($) {
  $(function () {
	$('#ai-quiz-update-style').on('click', function(){
		update_style();
	});
	$('#ai-quiz-reset-style').on('click', function(){
		reset_style();
	});
	function update_style(){
		var colors = {};
		var phrase = document.getElementById("ai-quiz-phrase").value;

		var accordion = document.querySelector('#accordionFlushExample');
		var colorInputs = accordion.querySelectorAll("input[type='color']");

		colorInputs.forEach(function(input) {
				colors[input.id] = input.value;
		});
		console.log(colors);
		$.ajax({
			url : 'admin-ajax.php',
			type: 'post',
			data: {
			  action: 'ai_quiz_update_style',
			  colors:  JSON.stringify(colors),
			  phrase: phrase
			},
			success: function(data) {
				console.log(data)
				if(data.exito){
					window.location.reload();
				}
			}
		});
	}
	function reset_style(){
		if(confirm("Are you sure youwant to reset to the default style values") == true){
			$.ajax({
				url : 'admin-ajax.php',
				type: 'post',
				data: {
				  action: 'ai_quiz_reset_style'
				},
				success: function(data) {
					console.log(data)
					if(data.exito){
						window.location.reload();
					}
				}
			});
		}
	}
    //Pagination
    const pagination = document.querySelector("#ai-quiz-pagination");
    let startY;
    let startX;
    let scrollLeft;
    let scrollTop;
    let isDown;

    pagination.addEventListener("mousedown", (e) => mouseIsDown(e));
    pagination.addEventListener("mouseup", (e) => mouseUp(e));
    pagination.addEventListener("mouseleave", (e) => mouseLeave(e));
    pagination.addEventListener("mousemove", (e) => mouseMove(e));

    function mouseIsDown(e) {
      isDown = true;
      startY = e.pageY - pagination.offsetTop;
      startX = e.pageX - pagination.offsetLeft;
      scrollLeft = pagination.scrollLeft;
      scrollTop = pagination.scrollTop;
    }
    function mouseUp(e) {
      isDown = false;
    }
    function mouseLeave(e) {
      isDown = false;
    }
    function mouseMove(e) {
      if (isDown) {
        e.preventDefault();
        //Move vertcally
        const y = e.pageY - pagination.offsetTop;
        const walkY = y - startY;
        pagination.scrollTop = scrollTop - walkY;

        //Move Horizontally
        const x = e.pageX - pagination.offsetLeft;
        const walkX = x - startX;
        pagination.scrollLeft = scrollLeft - walkX;
      }
    }



    function create_test_question(question, index) {
      var texto;
      texto = `
                  <div class="ai-quiz-question-card" id="ai-quiz-question-card" data-id=${question["id"]} data-arr=${index}>
            <div class="d-flex flex-column justify-content-center align-items-center">
              <div class="mb-5 ai-quiz-question-title">${question["question"]}</div>
              <div class="ai-quiz-options-card flex-column justify-content-center align-items-center w-100 text-center">
          `;
      for (var j = 0; j < question["options"].length; j++) {
        var option_class = "";
        if (
          "result" in questions_arr[index] &&
          questions_arr[index].result.user == question["options"][j]["id"]
        ) {
          option_class = "selected";
          if ("score" in questions_arr[index].result) {
            if (questions_arr[index].result.score == "1") {
              option_class += " success";
            } else {
              option_class += " failed";
            }
          }
        } else {
          if (
            "result" in questions_arr[index] &&
            "score" in questions_arr[index].result &&
            questions_arr[index].result.score == "0" &&
            questions_arr[index].result.answer == question["options"][j]["id"]
          ) {
            option_class += " success";
          }
        }
        texto += `
              <div class="ai-quiz-option my-3 ${option_class}" data-id="${
          question["options"][j]["id"]
        }" ${
          "result" in questions_arr[index] &&
          "score" in questions_arr[index].result
            ? ""
            : 'onclick="select_option(this)"'
        }>${question["options"][j]["answer"]}</div>`;
      }
      if (
        "result" in questions_arr[index] &&
        "score" in questions_arr[index].result &&
        questions_arr[index].explanation
      ) {
        texto += `<div class="ai-quiz-know-what">${questions_arr[index].explanation}</div>`;
      }
      texto += `
              </div>
              ${
                "result" in questions_arr[index] &&
                "score" in questions_arr[index].result
                  ? ""
                  : '<button id="ai-quiz-checkbutton" style="display:none;" class="btn btn-success">Check</button>'
              }
            </div>
          </div>
                  `;
      document.getElementById("ai-quiz-question").innerHTML = texto;

      $("#ai-quiz-checkbutton").on("click", function () {
        check_response("test");
      });

    }
    function check_response() {
      var question_id = document
        .querySelector(".ai-quiz-question-card")
        .getAttribute("data-id");
      var index = document
        .querySelector(".ai-quiz-question-card")
        .getAttribute("data-arr");
      var selected_option = document.querySelector(".ai-quiz-option.selected");
      var selected_option_id = selected_option.getAttribute("data-id");
      questions_arr[index].result = {
        user: selected_option_id,
        score: null,
        answer: null,
      };
      questions_arr[index].options.forEach((opt) => {
        if (opt.answer_bin == "1") {
          questions_arr[index].result.answer = opt.id;
        }
        if (selected_option_id == opt.id && opt.answer_bin == "1") {
          questions_arr[index].result.score = 1;
        } else if (selected_option_id == opt.id && opt.answer_bin == "0") {
          questions_arr[index].result.score = 0;
        }
      });

      loadQuestion(index);
      createNavigationButtons(false);
      document
        .querySelector(`.ai-quiz-question-circle[data-id="${index}"]`)
        .classList.add("active");
    }

    function loadQuestion(index) {
      console.log(questions_arr);
      var prev_btn = document.getElementById("ai-quiz-prev-btn");
      var next_btn = document.getElementById("ai-quiz-next-btn");
      // Elimina los eventos onclick de los botones
      prev_btn.onclick = null;
      next_btn.onclick = null;

      prev_btn.style.display = "none";
      next_btn.style.display = "none";
      var question = questions_arr[index];
      index = parseInt(index);
      create_test_question(question, index);



      //Next prev buttons
      if (index !== 0) {
        prev_btn.style.display = "block";
      }
      if (index !== questions_arr.length - 1) {
        next_btn.style.display = "block";
      }
      // Asigna las nuevas funciones a los botones
      prev_btn.onclick = function () {
        prevnext_btn(index, "prev");
      };

      next_btn.onclick = function () {
        prevnext_btn(index, "next");
      };
    }
    loadQuestion(0);
    createNavigationButtons();

    function prevnext_btn(index, type) {
      if (type == "next") {
        var n = index + 1;
      } else {
        var n = index - 1;
      }
      document
        .querySelector(`.ai-quiz-question-circle[data-id="${index}"]`)
        .classList.remove("active");
      document
        .querySelector(`.ai-quiz-question-circle[data-id="${n}"]`)
        .classList.add("active");
      loadQuestion(n);

      //Colocar los botones en zona visible del scroll
      var container = document.getElementById("ai-quiz-pagination");
      var button = document.querySelector(".ai-quiz-question-circle.active");

      // Si no hay botón activo, no hagas nada
      if (!button) return;

      var containerRect = container.getBoundingClientRect();
      var buttonRect = button.getBoundingClientRect();

      // Verifica si el botón está completamente visible en el contenedor
      var isCompletelyVisible =
        buttonRect.left >= containerRect.left &&
        buttonRect.right <= containerRect.right;

      // Si el botón no está completamente visible, desplázate hasta él
      if (!isCompletelyVisible) {
        container.scrollLeft =
          button.offsetLeft - (containerRect.width / 2 - buttonRect.width / 2);
      }
    }
    function createNavigationButtons(initial = true) {
      const paginationContainer = $("#ai-quiz-pagination");
      paginationContainer.html("");
      for (let i = 0; i < questions_arr.length; i++) {
        var act = initial && i == 0 ? "active" : "";
        var stat;
        if ("result" in questions_arr[i]) {
          if ("score" in questions_arr[i].result) {
            if (questions_arr[i].result.score > 0.5) {
              stat = "success";
            } else {
              stat = "failed";
            }
          } else {
            stat = "answered";
          }
        } else {
          stat = "pending";
        }
        const button = $(
          '<div data-id="' +
            i +
            '" class="ai-quiz-question-circle ' +
            stat +
            " m-3 " +
            act +
            '"><span></span></div>'
        );
        button.click(function () {
          $("#ai-quiz-pagination .ai-quiz-question-circle").removeClass(
            "active"
          );
          $(this).addClass("active");
          currentQuestionIndex = i;
          loadQuestion(currentQuestionIndex);
        });
        paginationContainer.append(button);
      }
    }

	var accordion = document.querySelector('#accordionFlushExample');
    var colorInputs = accordion.querySelectorAll("input[type='color']");

    colorInputs.forEach(function(input) {
        input.addEventListener("input", function() {
            document.documentElement.style.setProperty('--' + this.id, this.value);
        });
    });
  });
});
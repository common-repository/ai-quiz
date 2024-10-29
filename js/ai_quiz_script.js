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

class FaceRating {
  constructor(qs) {
    this.input = document.querySelector(qs);
    this.input?.addEventListener("input", this.update.bind(this));
    this.face = this.input?.previousElementSibling;
    this.scoreText = document.querySelector("#score-text");
    this.update();
  }
  update(e) {
    let value = this.input.defaultValue;

    // when manually set
    if (e) value = e.target?.value;
    // when initiated
    else this.input.value = value;

    const min = this.input.min || 0;
    const max = this.input.max || 100;
    const percentRaw = ((value - min) / (max - min)) * 100;
    const percent = +percentRaw.toFixed(2);

    this.input?.style.setProperty("--percent", `${percent}%`);

    // face and range fill colors
    const maxHue = 120;
    const hueExtend = 30;
    const hue = Math.round((maxHue * percent) / 100);

    let hue2 = hue - hueExtend;
    if (hue2 < 0) hue2 += 360;

    const hues = [hue, hue2];
    hues.forEach((h, i) => {
      this.face?.style.setProperty(`--face-hue${i + 1}`, h);
      this.scoreText?.style.setProperty(`--face-hue${i + 1}`, h);
    });

    this.input?.style.setProperty("--input-hue", hue);

    // emotions
    const duration = 1;
    const delay = (-(duration * 0.99) * percent) / 100;
    const parts = ["right", "left", "mouth-lower", "mouth-upper"];

    parts.forEach((p) => {
      const el = this.face?.querySelector(`[data-${p}]`);
      el?.style.setProperty(`--delay-${p}`, `${delay}s`);
    });

    // aria label
    const faces = [
      "Sad face",
      "Slightly sad face",
      "Straight face",
      "Slightly happy face",
      "Happy face",
    ];
    let faceIndex = Math.floor((faces.length * percent) / 100);
    if (faceIndex === faces.length) --faceIndex;

    this.face?.setAttribute("aria-label", faces[faceIndex]);
  }
}

jQuery(document).ready(function ($) {
  $(function () {
    const fr = new FaceRating("#face-rating");

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

    $("#ai-quiz-share").on("click", function () {
      if (document.getElementById("ai-quiz-custom-pop")) {
        document.getElementById("ai-quiz-custom-pop").remove();
      }
      const div = document.createElement("div");
      div.setAttribute("class", "ai-quiz-popup-container");

      div.setAttribute("id", "ai-quiz-pop");

      div.innerHTML = `
		<div class="ai-quiz-popup flex-column align-items-center" id="ai-quiz-pop-cont">
		<h5 class="mt-4 mb-5">Share your result!</h5>
			<div class="ai-quiz-social__links my-4">
			<button id="ai-quiz-share-twitter" class="ai-quiz-social__link" data-attr="twitter" data-link="https://www.twitter.com/share">
				<i class="fa-brands fa-square-twitter"></i>
			</button>
			<button id="ai-quiz-share-telegram" class="ai-quiz-social__link" data-attr="telegram" data-link="https://www.telegram.com/share">
				<i class="fa-brands fa-telegram"></i>
			</button>
			<button id="ai-quiz-share-whatsapp" class="ai-quiz-social__link" data-attr="whatsapp" data-link="https://www.whatsapp.com/share">
				<i class="fa-brands fa-square-whatsapp"></i>
			</button>
			<button id="ai-quiz-share-clipboard" class="ai-quiz-social__link" data-attr="linkedin" data-link="https://www.linkedin.com/share">
				<i class="fa-regular fa-paste"></i>
			</button>
			</div>
		</div>`;
		//Creamos el elemento donde va a pegarse el popup
		const div_pop = document.createElement("div");
		div_pop.setAttribute("id", "ai-quiz-custom-pop");
		document.body.appendChild(div_pop);

		//Añadimos el popup
		document.getElementById("ai-quiz-custom-pop").appendChild(div);

		document.getElementById("ai-quiz-pop").onclick = function (e) {
			container = document.getElementById("ai-quiz-pop-cont");

			if (container && container !== e.target && !container.contains(e.target)) {
			document.getElementById("ai-quiz-pop").remove();
			}
		};
		var text = "Quiz: " + aiQuiz.name +"\n\n";
		var sc = ""
		var count = 0;
		var scor = 0;
		questions_arr.forEach(q => {
			if('result' in q){
				if(q.result.score){
					sc+="🟩 ";
					scor++;
				}else{
					sc+="🟥 ";
				}
			}else{
				sc+="⬜ ";
			}
			count++;
		});
		text += "Score: " + scor + "/" + count + "\n\n";
		text += sc;
		var url = aiQuiz.url;
		var encodedText = encodeURIComponent(text);
		var encodedUrl = encodeURIComponent(url);
		//Añadir funciones

		document.getElementById("ai-quiz-share-twitter").addEventListener("click", function () {
			window.open(`https://twitter.com/intent/tweet?text=${encodedText}&url=${encodedUrl}`);
		});
		document.getElementById("ai-quiz-share-telegram").addEventListener("click", function () {
			window.open(`https://telegram.me/share/url?url=${encodedUrl}&text=${encodedText}`);
		});
		document.getElementById("ai-quiz-share-whatsapp").addEventListener("click", function () {
			window.open(`https://web.whatsapp.com/send?text=${encodedText}%0A%0A${encodedUrl}`);
		});
		document.getElementById("ai-quiz-share-clipboard").addEventListener("click", function () {
			navigator.clipboard.writeText(`${text}\n\n${url}`).then(function() {
				alert('Link copiado al portapapeles');
			  }, function() {
				alert('Error al copiar el link al portapapeles');
			  });
		});

    });

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
      document.getElementById("ai-quiz-share").style.display = "block";
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
      //Opciones para administrador
	  if(aiQuiz.admin){
		  add_options(index);
	  }


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
      upload_score(questions_arr);
    }
    function upload_score(arr) {
      var n_success = document.querySelectorAll(
        ".ai-quiz-question-circle.success"
      ).length;
      var n_failed = document.querySelectorAll(
        ".ai-quiz-question-circle.failed"
      ).length;
      var sum = n_success + n_failed;
      if (sum != 0) {
        const fr = new FaceRating("#face-rating");
        document.getElementById("success-score").innerHTML = n_success;
        document.getElementById("total-score").innerHTML = sum;
        document.getElementById("face-score").style.display = "flex";
        document.getElementById("face-rating").value = (n_success / sum) * 5;
        fr.update();
      }
    }

	function options_popup(x){
		const question_id = questions_arr[x].id;
		if (document.getElementById("ai-quiz-custom-pop")) {
			document.getElementById("ai-quiz-custom-pop").remove();
		}
		const div = document.createElement("div");
		div.setAttribute("class", "ai-quiz-popup-container");
		
		div.setAttribute("id", "ai-quiz-pop");
		
		div.innerHTML = `
		<div class="ai-quiz-popup flex-column align-items-center" id="ai-quiz-pop-cont">
		<button class="btn btn-danger my-2" id="delete_question_btn">Delete question</button>
		<button class="btn btn-danger my-2" id="delete_quiz_btn">Delete Quiz</button>
		<button class="btn btn-primary my-2" id="update_question_btn" >Edit Question</button>
		<div class="lds-dual-ring" id="o-loader"></div>
		</div>`;
		
		
		//Creamos el elemento donde va a pegarse el popup
		const div_pop = document.createElement("div");
		div_pop.setAttribute("id", "ai-quiz-custom-pop");
		document.body.appendChild(div_pop);
		
		//Añadimos el popup
		document.getElementById("ai-quiz-custom-pop").appendChild(div);
		
		document.getElementById('ai-quiz-pop').onclick = function(e) {
		
			container=document.getElementById('ai-quiz-pop-cont')
		
			if (container && container !== e.target && !container.contains(e.target)) {
		
			document.getElementById("ai-quiz-pop").remove();
		
			}
		
		}
		$("#delete_question_btn").on("click", function () {
			delete_question_ajax(x);
		});
		$("#delete_quiz_btn").on("click", function () {
			delete_quiz_ajax();
		});
		$("#update_question_btn").on("click", function () {
			update_question(x);
		});
	
	
	}
	function add_options(index){
		var optionsBtn = document.createElement('div');
		optionsBtn.classList.add("question-options", "p-2");
		optionsBtn.id = "ai-quiz-options-btn";
		optionsBtn.innerHTML = `<i class="fa fa-ellipsis-vertical"></i>`;
		optionsBtn.addEventListener("click", function() {
			options_popup(index); // Llamar a otraFuncion y pasarle el objeto d
		});
	
		document.getElementById("ai-quiz-question-card").appendChild(optionsBtn);
	}
	function update_question(index){
		$("#ai-quiz-pop").click();

		var question = questions_arr[index]
		var texto;
		texto = `
					<div class="ai-quiz-question-card" id="ai-quiz-question-card" data-id=${question["id"]} data-arr=${index}>
			  <div class="d-flex flex-column justify-content-center align-items-center">
				<textarea class="mb-5 ai-quiz-question-title form-control">${question["question"]}</textarea>
				<div class="ai-quiz-options-card flex-column justify-content-center align-items-center w-100 text-center">
			`;
		for (var j = 0; j < question["options"].length; j++) {
		  var option_class = "";
		  if(question["options"][j].answer_bin == "1"){
			option_class = "success";
		  }
		  
		  texto += `
				<textarea class="ai-quiz-option editing form-control my-3 ${option_class}" data-id="${
			question["options"][j]["id"]
		  }">${question["options"][j]["answer"]}</textarea>`;
		}
		texto += `<textarea class="ai-quiz-know-what editing form-control mx-0 my-4">${questions_arr[index].explanation}</textarea>`;
		texto += `
				</div>
				<div class="d-flex flex-row justify-content-center">
				<button id="ai-quiz-update" class="btn btn-success me-2">Update</button>
				<button id="ai-quiz-update-canceled" class="btn btn-danger">Cancel</button>
				</div>
			  </div>
			</div>
					`;
		document.getElementById("ai-quiz-question").innerHTML = texto;

		$("#ai-quiz-update").on("click", function () {
		  update_question_ajax(index);
		});
		$("#ai-quiz-update-canceled").on("click", function () {
		  loadQuestion(index);
		});

	}
	function delete_question_ajax(index){

		$.ajax({
			url : aiQuiz.admin_ajax_url,
			type: 'post',
			data: {
			  action: 'ai_quiz_delete_question', id: questions_arr[index].id
			},
			success: function(data) {
				//window.location.href = aiQuiz.url
				window.location.reload()
			}
		});
	}
	function delete_quiz_ajax(){
		$.ajax({
			url : aiQuiz.admin_ajax_url,
			type: 'post',
			data: {
			  action: 'ai_quiz_delete_quiz', id: aiQuiz.id
			},
			success: function(data) {
				window.location.reload()
			}
		});
	}
	function update_question_ajax(index){
		// Creamos el objeto donde se guardarán los datos
		let data_ajax = {
			id: questions_arr[index].id,
			quiz_id: aiQuiz.id,
			question: '',
			explanation: '',
			options: []
		};

		// Obtenemos todas las textareas
		let textareas = $('#ai-quiz-question-card textarea');

		// Establecemos el valor de 'question' con el valor de la primera textarea
		data_ajax.question = textareas.first().val();

		// Establecemos el valor de 'result' con el valor de la última textarea
		data_ajax.explanation = textareas.last().val();

		// Llenamos 'options' con los valores de las textareas intermedias
		textareas.slice(1, -1).each(function() {
			let opt = {
				id: $(this).attr('data-id'),
				answer: $(this).val()
			};
			data_ajax.options.push(opt);
		});
		console.log(data_ajax)
		$.ajax({
			url : aiQuiz.admin_ajax_url,
			type: 'post',
			data: {
			  action: 'ai_quiz_update_question', question: data_ajax
			},
			success: function(data) {
				console.log(data)
				if(data.exito){
					questions_arr[index] = data.question;
					loadQuestion(index)
				}
			}
		});
	}
	
  });
});

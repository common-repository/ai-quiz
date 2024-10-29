var intervalId_table = 0;
var all_quizs = [];
jQuery(document).ready(function($) { $(function() {
	//Btn onclick functions
	$('#ai-quiz-choose-post-btn').on('click', function(){
		document.getElementById("ai-quiz-choose").style.display="none";
		document.getElementById("ai-quiz-choose-post").style.display="block";
		ai_quiz_get_posts();
	});
	$('#ai-quiz-choose-custom-btn').on('click', function(){
		document.getElementById("ai-quiz-choose").style.display="none";
		document.getElementById("ai-quiz-choose-custom").style.display="block";
		document.getElementById("ai-quiz-choose-idiom-custom").innerHTML = ai_choose_language();
	});
	$('.ai-quiz-back-choose').on('click', function(){
		document.getElementById("ai-quiz-choose-post").style.display="none";
		document.getElementById("ai-quiz-choose-custom").style.display="none";
		document.getElementById("ai-quiz-choose").style.display="block";
		document.getElementById("ai-quiz-choose-idiom-custom").innerHTML = ""
	});

	$('#ai-quiz-choose-pdf-btn').on('click', function(){
		$(this).addClass("ai-quiz-selected");
		document.getElementById("ai-quiz-choose-url-btn").classList.remove("ai-quiz-selected");
		document.getElementById("ai-quiz-choose-topic-btn").classList.remove("ai-quiz-selected");
		document.getElementById("ai-quiz-pdf-show").style.display="flex";
		document.getElementById("ai-quiz-create-custom-btn").style.display="block";
		document.getElementById("ai-quiz-create-custom-btn").setAttribute("data-custom", "pdf");
		document.getElementById("ai-quiz-url-show").style.display="none";
		document.getElementById("ai-quiz-topic-show").style.display="none";
	});
	$('#ai-quiz-choose-url-btn').on('click', function(){
		$(this).addClass("ai-quiz-selected");
		document.getElementById("ai-quiz-choose-pdf-btn").classList.remove("ai-quiz-selected");
		document.getElementById("ai-quiz-choose-topic-btn").classList.remove("ai-quiz-selected");
		document.getElementById("ai-quiz-url-show").style.display="block";
		document.getElementById("ai-quiz-create-custom-btn").style.display="block";
		document.getElementById("ai-quiz-create-custom-btn").setAttribute("data-custom", "url");
		document.getElementById("ai-quiz-pdf-show").style.display="none";
		document.getElementById("ai-quiz-topic-show").style.display="none";
	});
	$('#ai-quiz-choose-topic-btn').on('click', function(){
		$(this).addClass("ai-quiz-selected");
		document.getElementById("ai-quiz-choose-url-btn").classList.remove("ai-quiz-selected");
		document.getElementById("ai-quiz-choose-pdf-btn").classList.remove("ai-quiz-selected");
		document.getElementById("ai-quiz-topic-show").style.display="block";
		document.getElementById("ai-quiz-create-custom-btn").style.display="block";
		document.getElementById("ai-quiz-create-custom-btn").setAttribute("data-custom", "topic");
		document.getElementById("ai-quiz-url-show").style.display="none";
		document.getElementById("ai-quiz-pdf-show").style.display="none";
	});
	$('#ai-quiz-create-custom-btn').on('click', function(){
		ai_quiz_create_custom_btn(this);
	});
	create_drop_zone();
	
	//Get posts
	function ai_quiz_get_posts(){
		var post_table = "";
		$('#ai-quiz-posts-table').DataTable().destroy();
		$('#ai-quiz-posts-table-tbody').empty();
		var post_table = "";
		$.ajax({
			url : 'admin-ajax.php',
			type: 'get',
			dataType: 'json',
			data: {
			  action: 'ai_quiz_get_posts'
			},
			success: function(data) {
				console.log(data)
				for (var i = 0; i < data.posts.length; i+=1) {
					post_table+=`<tr><td class="ai-quiz-create-by-post-button" style="cursor:pointer;" data-pos = "${i}">${data.posts[i].post_title}</td></tr>`;
				}
				$('#ai-quiz-posts-table').DataTable().destroy();
				$('#ai-quiz-posts-table').find('tbody').append(post_table);
				$('#ai-quiz-posts-table').DataTable({"lengthChange": false}).draw();
				//Order by date clicking twice
				add_functions_to_post_table(data.posts);
				$('#ai-quiz-posts-table').on( 'draw.dt', function () {
					add_functions_to_post_table(data.posts)
				} );
			}
		});
	}

	function add_functions_to_post_table(arr){
		$(".ai-quiz-create-by-post-button").off("click");
		$(".ai-quiz-create-by-post-button").on("click", function() {
			create_by_post_popup(arr[$(this).attr("data-pos")])
		});
	}
	function add_functions_to_quiz_table(arr){
		//console.log(arr);
		$(".ai-quiz-see-quiz-button").off("click");
		$(".ai-quiz-see-quiz-button").on("click", function() {
			var el = $(this);
			el.addClass("ai-quiz-loading");
			$.ajax({
				url : 'admin-ajax.php',
				type: 'post',
				data: {
				  action: 'ai_quiz_create_shortcode_ajax',
				  id: arr[$(this).attr("data-pos")].id
				},
				success: function(data) {
					document.getElementById("ai-quiz-shortcode").innerHTML = data;
					el.removeClass("ai-quiz-loading");
					document.getElementById('ai-quiz-shortcode').scrollIntoView({
						behavior: 'smooth'
					});
				}
			});
			
		});
		$(".ai-quiz-copy-quiz-button").off("click");
		$(".ai-quiz-copy-quiz-button").on("click", function() {
			create_copy_shortcode_popup(arr[$(this).attr("data-pos")])
		});
		$(".ai-quiz-url-button").off("click");
		$(".ai-quiz-url-button").on("click", function() {
			window.location.href = $(this).attr("data-url")
		});
		$(".ai-quiz-failed-button").off("click");
		$(".ai-quiz-failed-button").on("click", function() {
			create_failed_popup(arr[$(this).attr("data-pos")])
		});
	}
	function create_copy_shortcode_popup(quiz){
		if (document.getElementById("ai-quiz-custom-pop")) {
			document.getElementById("ai-quiz-custom-pop").remove();
		  }
		  const div = document.createElement("div");
		  div.setAttribute("class", "ai-quiz-popup-container");
		
		  div.setAttribute("id", "ai-quiz-pop");
		
		  div.innerHTML = `
		  <div class="ai-quiz-popup flex-column align-items-center" id="ai-quiz-pop-cont">
		  <h5 class="mt-4 mb-3">Copy and paste this code!</h5>
		  <b class="bold my-3">Add the quiz wherever you want</b>
		  <div class="input-group mb-3">
          <input type="text" id="ai_quiz_shortcode_code" class="form-control" value='[ai_quiz id="${quiz.id}"]' readonly>
          <div class="input-group-append">
            <button class="ms-2 btn btn-primary" type="button" id="ai-quiz-copyButton-shortcode"><i class=" fa-regular fa-paste"></i></button>
          </div>
        </div>
        <div id="copyAlert" class="alert alert-info mt-3" style="display: none; opacity: 1; transition: opacity 0.5s linear;">
          Shortcode successfully copied to clipboard
        </div>
		  </div>`;
		  //Creamos el elemento donde va a pegarse el popup
		  const div_pop = document.createElement("div");
		  div_pop.setAttribute("id", "ai-quiz-custom-pop");
		  document.body.appendChild(div_pop);
		
		//Añadimos el popup
		document.getElementById("ai-quiz-custom-pop").appendChild(div);
		
		document.getElementById('ai-quiz-pop').onclick = function(e) {
		
			container=document.getElementById('ai-quiz-pop-cont')
		
			if (container !== e.target && !container.contains(e.target)) {
		
			document.getElementById("ai-quiz-pop").remove();
		
			}
		
		}
		document.querySelector("#ai-quiz-copyButton-shortcode").addEventListener("click", function() {
			var ai_quiz_shortcode_code = document.querySelector("#ai_quiz_shortcode_code");
			ai_quiz_shortcode_code.select();
			document.execCommand("copy");
		
			// Mostrar alerta de copiado y desaparecer después de 5 segundos
			var copyAlert = document.querySelector("#copyAlert");
			copyAlert.style.display = "block";
			setTimeout(function() {
			  copyAlert.style.opacity = "0";
			  setTimeout(function(){
				copyAlert.style.display = "none";
				copyAlert.style.opacity = "1";
			  }, 500);
			}, 5000);
		  });
	}
	function create_failed_popup(quiz){
		if (document.getElementById("ai-quiz-custom-pop")) {
			document.getElementById("ai-quiz-custom-pop").remove();
		  }
		  const div = document.createElement("div");
		  div.setAttribute("class", "ai-quiz-popup-container");
		
		  div.setAttribute("id", "ai-quiz-pop");
		
		  div.innerHTML = `
		  <div class="ai-quiz-popup flex-column align-items-center" id="ai-quiz-pop-cont">
		  <h5 class="mt-4 mb-3">Creation failed!</h5>
		  <p>The creation of the quiz has failed ${quiz.n_retrys} times.<br/> We probably have a long queue on the server. <br/>We will try again 5 more times.
		  If the quiz has not been created by the 5th time, we will refund your credit.</p>
		  </div>`;
		  //Creamos el elemento donde va a pegarse el popup
		  const div_pop = document.createElement("div");
		  div_pop.setAttribute("id", "ai-quiz-custom-pop");
		  document.body.appendChild(div_pop);
		
		//Añadimos el popup
		document.getElementById("ai-quiz-custom-pop").appendChild(div);
		
		document.getElementById('ai-quiz-pop').onclick = function(e) {
		
			container=document.getElementById('ai-quiz-pop-cont')
		
			if (container !== e.target && !container.contains(e.target)) {
		
			document.getElementById("ai-quiz-pop").remove();
		
			}
		
		}
	}
	function ai_choose_language(defaultLang = 'en_US'){
		if( ai_quiz_user && 'idiom' in  ai_quiz_user){
			defaultLang = ai_quiz_user.idiom;
		}
		var languages = {
			'ar_SA': 'Arabic',
			'bn_BD': 'Bengali',
			'zh_CN': 'Chinese (Simplified)',
			'en_US': 'English (US)',
			'fr_FR': 'French',
			'de_DE': 'German',
			'hi_IN': 'Hindi',
			'it_IT': 'Italian',
			'jv_ID': 'Javanese',
			'ja_JP': 'Japanese',
			'ko_KR': 'Korean',
			'mr_IN': 'Marathi',
			'pa_IN': 'Punjabi',
			'pt_BR': 'Portuguese (Brazil)',
			'ru_RU': 'Russian',
			'es_ES': 'Spanish',
			'ta_IN': 'Tamil',
			'te_IN': 'Telugu',
			'tr_TR': 'Turkish',
			'ur_PK': 'Urdu',
			'vi_VN': 'Vietnamese',
		};
		
		var orderedLanguages = Object.keys(languages).sort().reduce(
			(obj, key) => { 
				obj[key] = languages[key]; 
				return obj;
			},
			{}
		);
		var selectHTML = "<select id='ai-quiz-select-language'>";
		for(var langCode in orderedLanguages) {
			selectHTML += `<option value="${langCode}" ${langCode === defaultLang ? 'selected' : ''}>${orderedLanguages[langCode]}</option>`;
		}
		selectHTML += "</select>";
		return selectHTML;
	}
	function create_by_post_popup(post){
		console.log(post.ID)
		if (document.getElementById("ai-quiz-custom-pop")) {
			document.getElementById("ai-quiz-custom-pop").remove();
		  }


		  const div = document.createElement("div");
		  div.setAttribute("class", "ai-quiz-popup-container");
		
		  div.setAttribute("id", "ai-quiz-pop");
		  console.log(ai_quiz_user)
		  div.innerHTML = `
		  <div class="ai-quiz-popup flex-column align-items-center" id="ai-quiz-pop-cont">
		  <h5 class="mt-4 mb-3">Create new quiz from post</h5>
		  <b class="bold my-3">${post.post_title}</b>
		  <p>Select idiom</p>
		  ${ai_choose_language()}
		  <button class="btn btn-primary mt-3" id="btn_create_quiz_post">Create Quiz</button>
		  <div class="ai-quiz-loader" id="loader-new-quiz-post"></div>
		  <div id="error_new_quiz_post"></div>
		  </div>`;
		  //Creamos el elemento donde va a pegarse el popup
		  const div_pop = document.createElement("div");
		  div_pop.setAttribute("id", "ai-quiz-custom-pop");
		  document.body.appendChild(div_pop);
		
		//Añadimos el popup
		document.getElementById("ai-quiz-custom-pop").appendChild(div);
		
		document.getElementById('ai-quiz-pop').onclick = function(e) {
		
			container=document.getElementById('ai-quiz-pop-cont')
		
			if (container !== e.target && !container.contains(e.target)) {
		
			document.getElementById("ai-quiz-pop").remove();
		
			}
		
		}
		//Añadir nueva función al boton
		var boton = document.getElementById("btn_create_quiz_post");
		boton.addEventListener("click", function() {
			create_quiz_by_post(this, post); // Llamar a otraFuncion y pasarle el objeto d
		});
	}
	function create_quiz_by_post(x,post){
		console.log(post)
		var btn = x;
		var err = document.getElementById("error_new_quiz_post");
		var loader = document.getElementById("loader-new-quiz-post");
		btn.style.display="none";
		loader.style.display="block";
		err.style.display="none";
		$.ajax({
			url : 'admin-ajax.php',
			type: 'post',
			dataType: 'json',
			data: {
			  action: 'ai_quiz_create_by_post', id: post.ID, idiom: document.getElementById("ai-quiz-select-language").value
			},
			success: function(data) {
				console.log(data)
				btn.style.display="none";
				loader.style.display="none";
				err.style.display="none";
				if (document.getElementById("ai-quiz-custom-pop")) {
					document.getElementById("ai-quiz-custom-pop").remove();
				}
				$('.ai-quiz-back-choose').click()
				$("#pills-train-tab").click();
				ai_quiz_get_posts();
				ai_quiz_get_quizs();
				ai_quiz_get_info();
			}
		});
	}

	function ai_quiz_create_custom_btn(x){

		var btn = x;
		var custom_type = x.getAttribute("data-custom")
		var file = false;
		var url = false;
		var topic = false;
		if(custom_type == "pdf"){
			if (document.getElementById("myFile").files.length > 0){
				file = document.getElementById("myFile").files[0];
				document.getElementById("ai-quiz-drop-zone").classList.remove("ai-quiz-required");
			}else{
				document.getElementById("ai-quiz-drop-zone").classList.add("ai-quiz-required");
				return;
			}
		}else if (custom_type == "url"){
			url = document.getElementById("ai-quiz-url-input").value
			if(url==""){
				document.getElementById("ai-quiz-url-input").classList.add("ai-quiz-required");
				return;
			}else{
				document.getElementById("ai-quiz-url-input").classList.remove("ai-quiz-required");
			}
		}else if(custom_type == "topic"){
			topic = document.getElementById("ai-quiz-topic-input").value
			if(topic==""){
				document.getElementById("ai-quiz-topic-input").classList.add("ai-quiz-required");
				return;
			}else{
				document.getElementById("ai-quiz-topic-input").classList.remove("ai-quiz-required");
			}
		}
		var err = document.getElementById("ai-quiz-err-custom");
		var loader = document.getElementById("ai-quiz-loader-custom");
		btn.style.display="none";
		loader.style.display="block";
		err.innerHTML="";
		$.ajax({
			url : 'admin-ajax.php',
			type: 'post',
			dataType: 'json',
			contentType: false,
			processData: false,
			data: function() {
				var data = new FormData();
				data.append('action', 'ai_quiz_create_by_custom');
				data.append('custom_type', custom_type);
				data.append('url', url);
				data.append('topic', topic);
				data.append('idiom', document.getElementById("ai-quiz-select-language").value);
				if (file) {
					data.append('file', file);
				}
				return data;
			}(),
			success: function(data) {
				console.log(data)
				if(data.exito){
					btn.style.display="block";
					loader.style.display="none";
					err.innerHTML="";
					$('.ai-quiz-back-choose').click()
					$("#pills-train-tab").click();
					ai_quiz_get_quizs();
					ai_quiz_get_info();
				}else{
					btn.style.display="block";
					loader.style.display="none";
					err.innerHTML=data.error;
				}
			}
		});
		
	}

	//Get quizs
	function ai_quiz_get_quizs(){
		var loader = document.getElementById("ai-quiz-loader-table")
		loader.style.display="block";
		var quiz_table = "";
		var is_creating = 0;
		all_quizs = []
		$('#ai-quizs-table').DataTable().destroy();
		$('#ai-quizs-table-tbody').empty();
		$.ajax({
			url : 'admin-ajax.php',
			type: 'get',
			dataType: 'json',
			data: {
				action: 'ai_quiz_get_quizs'
			},
			success: function(data) {
				console.log(data)
				all_quizs.push(...data.quizs)
				for (var i = 0; i < all_quizs.length; i+=1) {
					quiz_table+=`<tr>
					<td class="ai-quiz-create-by-post-button" data-pos = "${i}">${all_quizs[i].name}</td>
					`;
					if(all_quizs[i].status != "created"){
						is_creating = 1;
						quiz_table +=`<td>
						<i class="fa fa-spinner me-2 p-2 ai-quiz-blue ai-quiz-loading">
						</i>`;
						if('n_retrys' in all_quizs[i] && (all_quizs[i].n_retrys > 0)){
							quiz_table+=`<i class="fa fa-question-circle me-2 p-2 ai-quiz-blue ai-quiz-failed-button" style="cursor:pointer;" data-pos = "${i}">
							</i>`;
						}
						quiz_table += `</td>`;
					}else if(all_quizs[i].status == "created"){
						quiz_table+=`<td>
						<i class="fa fa-eye me-2 p-2 ai-quiz-blue ai-quiz-see-quiz-button" style="cursor:pointer;"  data-pos = "${i}">
						</i>
						<i class="fa fa-copy me-2 p-2 ai-quiz-blue ai-quiz-copy-quiz-button" style="cursor:pointer;" data-pos = "${i}">
						</i>`;
						
						if(all_quizs[i].post_url){
							quiz_table+=`<i class="fa fa-arrow-up-right-from-square me-2 p-2 ai-quiz-blue ai-quiz-url-button" style="cursor:pointer;" data-url = "${all_quizs[i].post_url}">
							</i>`;
						}
						quiz_table += `</td>`;
					}
					quiz_table+="</tr>";
				}
				$('#ai-quizs-table').DataTable().destroy();
				loader.style.display="none";
				$('#ai-quizs-table').find('tbody').append(quiz_table);
				$('#ai-quizs-table').DataTable({"lengthChange": false, order: []}).draw();
				//Order by date clicking twice
				add_functions_to_quiz_table(all_quizs);
				if(is_creating){
					//Reload table each 20 secs
					if(intervalId_table){
						clearInterval(intervalId_table)
					}
					intervalId_table = window.setInterval(function(){
						ai_quiz_get_quizs();
					  }, 20000);
				}else{
					if(intervalId_table){
						clearInterval(intervalId_table)
					}
				}
			}
		});
	}
	ai_quiz_get_quizs();
	$('#ai-quizs-table').on( 'draw.dt', function () {
		add_functions_to_quiz_table(all_quizs)
	} );

	//PDF INPUT

	function create_drop_zone(){
	  document.querySelectorAll(".ai-quiz-drop-zone__input").forEach((inputElement) => {
	  const dropZoneElement = inputElement.closest(".ai-quiz-drop-zone");
	
	  dropZoneElement.addEventListener("click", (e) => {
		inputElement.click();
	  });
	
	  inputElement.addEventListener("change", (e) => {
		if (inputElement.files.length && Validate(inputElement.files)) {
		  updateThumbnail(dropZoneElement, inputElement.files[0]);
		}
	  });
	
	  dropZoneElement.addEventListener("dragover", (e) => {
		e.preventDefault();
		dropZoneElement.classList.add("ai-quiz-drop-zone--over");
	  });
	
	  ["dragleave", "dragend"].forEach((type) => {
		dropZoneElement.addEventListener(type, (e) => {
		  dropZoneElement.classList.remove("ai-quiz-drop-zone--over");
		});
	  });
	
	  dropZoneElement.addEventListener("drop", (e) => {
		e.preventDefault();
	
		if (e.dataTransfer.files.length) {
		  inputElement.files = e.dataTransfer.files;
		  if(Validate(inputElement.files)){
			  updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
		  }
		}
	
		dropZoneElement.classList.remove("ai-quiz-drop-zone--over");
	  });
	});  
	}
	
	
	/**
	 * Updates the thumbnail on a drop zone element.
	 *
	 * @param {HTMLElement} dropZoneElement
	 * @param {File} file
	 */
	function updateThumbnail(dropZoneElement, file) {
	  let thumbnailElement = dropZoneElement.querySelector(".ai-quiz-drop-zone__thumb");
	
	  // First time - remove the prompt
	  if (dropZoneElement.querySelector(".ai-quiz-drop-zone__prompt")) {
		dropZoneElement.querySelector(".ai-quiz-drop-zone__prompt").remove();
	  }
	
	  // First time - there is no thumbnail element, so lets create it
	  if (!thumbnailElement) {
		thumbnailElement = document.createElement("div");
		thumbnailElement.classList.add("ai-quiz-drop-zone__thumb");
		dropZoneElement.appendChild(thumbnailElement);
	  }
	
	  thumbnailElement.dataset.label = file.name;
	  thumbnailElement.classList.add("ai-quiz-bg-pdf");
	  
	}
	function Validate(arrInputs) {
		i = 0;
		var oInput = arrInputs[i];
		if (oInput.type != "application/pdf") {
			console.log("El archivo debe ser formato pdf");
			return false;
		}
		var max_size = 50 //50 MB
		var sizeInMB = (oInput.size / (1024*1024)).toFixed(2);
		console.log(sizeInMB);
		if (sizeInMB > max_size) {
			console.log("El archivo es demasiado grande");
			return false;
		}
		file = oInput;
		return true;
	}

});});
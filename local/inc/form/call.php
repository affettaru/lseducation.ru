
<div class="magnific-popup magnific-popup--sm _p-md _def-p-def">
	<div class="title title--middle title--bot-line _pb-ms _mb-md _def-pb-md _def-mb-md">Есть вопросы?</div>
		<form class="form js-form js-video-after-form-letter01" data-ajax="/form/questions" method="post" data-analytics='{"type":"haveQuestions"}'>
			<div class="form-group _mb-ms _def-md-md">
				<label class="form-label" for="callback-phone">Номер телефона *</label>
				<div class="form-group _mb-ms _def-md-md">
					<input type="text"  name="callback-phone" id="callback-phone" required class="form-control phone-masked" data-msg-phone="Введите пожалуйста номер телефона для связи с Вами" data-msg-required="Введите пожалуйста номер телефона для связи с Вами" placeholder="Номер телефона" autofocus>
				</div>
			</div>
			<div class="form-group _mb-ms _def-md-md">
				<label class="form-label" for="callback-tel">Дополнительные данные для связи</label>
				<div class="form-controller">
					<input type="text" data-name="other" name="callback-tel" id="callback-tel" class="form-control" minlength="3" placeholder="Email">
				</div>
			</div>
			<div class="form-group _mb-ms _def-md-md">
				<label class="form-label" for="callback-text">Уточните вопрос</label>
				<div class="form-controller">
					<textarea data-name="text" name="callback-text" id="callback-text" rows="4" class="form-control"></textarea>
				</div>
			</div>
			<div class="form-group _mb-ms _def-md-md">
				<div class="form-controller">
					<label class="grid _items-center _flex-nowrap">
						<div class="custom-checkbox _mr-sm _flex-noshrink">
							<input type="checkbox" class="custom-checkbox__input" name="callback-policy" id="callback-policy" required>
							<span class="custom-checkbox__checked">✔</span>
						</div>
						<div class="text-b11">Я согласен на обработку моих данных. <a target="_blank" href="/ua/politika-konfidentsialnosti">Подробнее</a></div>
					</label>
					<label id="callback-policy-error" class="has-error has-error--visible" for="callback-policy" style="display: none;"></label>
				</div>
			</div>
			<div class="form-group">
				<button type="button" class="button button--yellow button--wide js-button-video"><span class="button__text">Отправить</span></button>
			</div>
		</form>
</div>


<div class="magnific-popup magnific-popup--lg _p-md _def-p-def">
<div class="title title--middle _mb-md _text-center">Что Вас интересует?</div>
<form class="form js-form" method="post" data-ajax="/form/orderServiceNew" data-analytics='{"type":"orderService"}'>
<div class="grid grid--hspace-def _justify-center">
<div class="_col-12">
<div class="form-group _mb-ms _def-md-md">
<label class="text-b11" for="callback-service">Выберите услугу:</label>
<div class="form-controller">
<select class="form-control form-control--arrow" name="type" data-name="type" id="callback-service">
<option value="Web">Разработка сайта</option>
<option value="SEO">Продвижение сайта</option>
<option value="Mobile">Разработка мобильного приложения</option>
<option value="UX">Проектирование</option>
<option value="SMM">SMM-продвижение</option>
<option value="PPC">Контекстная реклама</option>
<option value="DC">Комплексный интернет-маркетинг</option>
<option value="QA">Тестирование</option>
<option value="Branding">Брендинг</option>
<option value="Support">Техническая поддержка</option>
<option value="Other"selected>Дополнительные услуги</option>
</select>
</div>
</div>
</div>
<div class="_col-12 _md-col-6">
<div class="form-group _mb-ms _def-md-md">
<div class="form-controller">
<input type="text" data-name="phone" name="callback-phone" id="callback-phone" required class="form-control js-phone-input" data-rule-phone="true" data-msg-phone="Введите пожалуйста номер телефона для связи с Вами" data-msg-required="Введите пожалуйста номер телефона для связи с Вами" placeholder="Номер телефона *" autofocus>
</div>
</div>
<div class="form-group _mb-ms _def-md-md">
<div class="form-controller">
<input type="text" data-name="name" name="callback-name" id="callback-name" class="form-control" data-rule-word="true" placeholder="Представьтесь, пожалуйста" autofocus>
</div>
</div>
<div class="form-group _mb-ms _def-md-md">
<div class="form-controller">
<input type="text" data-name="other" name="callback-other" id="callback-other" class="form-control" minlength="3" placeholder="Email или skype">
</div>
</div>
<div class="form-group _mb-ms _def-md-md">
<div class="form-controller custom-file">
<input type="file" data-name="file" id="callback-file" name="callback-file" class="form-control custom-file__input js-custom-file" accept=".docx,.doc,.pdf,.jpg" data-rule-filetype="docx|doc|pdf|jpg" data-msg-filetype="Допустимые расширения файлов: doc, pdf, jpg">
 <label for="callback-file" class="form-control custom-file__result js-custom-file-result" data-text='{"default": "+ Прикрепить файл (doc, pdf, jpg) ", "changed": "Выбранный файл: %s (%s kb)"}'>+ Прикрепить файл (doc, pdf, jpg)</label>
<div class="custom-file__clear js-custom-file-clear">
<svg class="svg-icon" viewBox="0 0 39 39" width="1rem" height="1rem">
<use xlink:href="/Media/assets/images/sprites/icons.svg?v=1619791510#close"></use>
</svg>
</div>
<label id="callback-file-error" class="has-error" for="callback-file"></label>
</div>
</div>
</div>
<div class="_col-12 _md-col-6 _mb-ms _def-md-md">
<div class="form-group _height">
<div class="form-controller _height">
<textarea data-name="text" id="callback-text" name="callback-text" class="form-control _min-height" placeholder="Напишите все, что считаете нужным" data-rule-minlength="10"></textarea>
</div>
</div>
</div>
<div class="form-group _mb-ms _def-md-md">
<div class="form-controller">
<label class="grid _items-center _flex-nowrap">
<div class="custom-checkbox _mr-sm _flex-noshrink">
<input type="checkbox" class="custom-checkbox__input" name="callback-policy" id="callback-policy" required>
<span class="custom-checkbox__checked">✔</span>
</div>
<div class="text-b11">Я согласен на обработку моих данных. <a target="_blank" href="/politika-konfidentsialnosti">Подробнее</a></div>
</label>
<label id="callback-policy-error" class="has-error has-error--visible" for="callback-policy" style="display: none;"></label>
</div>
</div>
<div class="_col-12 _text-center">
<input type="hidden" data-name="service_name" value="Заказ услуги из главной формы">
<input type="hidden" data-name="token" value="cD5s11ae9W5MYw4Uscst3GSDRv2gdk2UMinV+uI36VCwH8e/NAkRNwWQEID+vtzbpW4hcQ1NB+nDIcEzh/+dp+rxhgm7DAgDq/M2qtnigr0="/>
<div class="form-group">
<button type="submit" class="button button--yellow">
<span class="button__text">Заказать</span>
</button>
</div>
</div>
</div>
</form>
</div>

<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<!--____________________________________________________-->
	<!--1. Специализации                                    -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalSpecializations')" :modal="modalSpecializations">
		<template #title>
			<span class="create" v-if="modalSpecializations.type == 'create'">
				СПЕЦИАЛИЗАЦИЯ (СОЗДАНИЕ)
			</span>
			<span v-if="modalSpecializations.type == 'edit'">СПЕЦИАЛИЗАЦИИ</span>
		</template>
		<template #body>
			<!-- Список специализаций -->
			<div class="specializations-list">
				<label class="item">
					<div></div>
					<div></div>
					<div>Название</div>
				</label>
				<!-- HACK Сделать соритровку по алфавиту -->
				<label
					class="item"
					v-for="(specialization, index) in getSortedSpecializations"
					:key="specialization.id"
					:class="{ active: cheked.specializations.includes(specialization.id) }"
				>
					<div>
						#{{
							index +
							1 +
							paginationSpecializations.elements.range *
								(paginationSpecializations.pages.current - 1)
						}}
					</div>
					<input type="checkbox" :value="specialization.id" v-model="cheked.specializations" />
					<div>{{ specialization.name }}</div>
				</label>
			</div>

			<!-- Пагинация -->
			<pagination
				:arrayLength="sections.specializations.length"
				:settings="paginationSpecializations"
				@changePage="changePageSpecializations"
			/>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="" v-if="modalSpecializations.type == 'create'">
					Создать
				</button-claim>
				<button-default
					@click="updateSpecialization"
					v-if="modalSpecializations.type == 'edit'"
				>
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>
	<!--____________________________________________________-->
	<!--2. Клиники                                          -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalClinics')" :modal="modalClinics">
		<template #title>
			<span class="create" v-if="modalClinics.type == 'create'"> СПЕЦИАЛИЗАЦИЯ (СОЗДАНИЕ) </span>
			<span v-if="modalClinics.type == 'edit'">КЛИНИКИ</span>
		</template>
		<template #body>
			<!-- Список специализаций -->
			<div class="clinics-list">
				<label class="item">
					<div></div>
					<div></div>
					<div>Название</div>
					<div>Статус приёма</div>
				</label>
				<!-- HACK Сделать соритровку по алфавиту -->
				<label
					class="item"
					v-for="(clinic, index) in getSortedClinics"
					:key="clinic.id"
					:class="{ active: cheked.clinics.includes(clinic.id) }"
				>
					<div>
						#{{
							index +
							1 +
							paginationClinics.elements.range * (paginationClinics.pages.current - 1)
						}}
					</div>
					<input
						type="checkbox"
						:id="`clin-${clinic.id}`"
						:value="clinic.id"
						v-model="cheked.clinics"
					/>
					<div>{{ clinic.name }}</div>
					<select v-model="clinic.priem">
						<option :value="0">Нет</option>
						<option :value="1">Да</option>
					</select>
				</label>
			</div>
			<!-- Пагинация -->
			<pagination
				:arrayLength="sections.clinics.length"
				:settings="paginationClinics"
				@changePage="changePageClinics"
			/>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="" v-if="modalClinics.type == 'create'"> Создать </button-claim>
				<button-default @click="updateClinics" v-if="modalClinics.type == 'edit'">
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>
	<!--____________________________________________________-->
	<!--3. Сертификаты                                      -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalCertificates')" :modal="modalCertificates">
		<template #title>
			<span class="create" v-if="modalCertificates.type == 'create'">
				СЕРТИФИКАТ (СОЗДАНИЕ)
			</span>
			<span v-if="modalCertificates.type == 'edit'">СЕРТИФИКАТ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<container-input-once :type="modalCertificates.type == 'create' ? 'create' : 'edit'">
				<template #title>
					<span>НАЗВАНИЕ*</span>
					<span v-if="currentCertificate.data.name.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="text"
						placeholder="Название должности"
						:class="{ error: currentCertificate.errors.name.status }"
						v-model="currentCertificate.data.name.body"
						@input="currentCertificate.data.name.edited = true"
						@blur="checkModalInput('currentCertificate', 'name', 'text')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.name.body">
						{{ currentCertificate.errors.name.body }}
					</span>
				</template>
			</container-input-once>
			<!-- Организация -->
			<container-input-once :type="modalCertificates.type == 'create' ? 'create' : 'edit'">
				<template #title>
					<span>ОРГАНИЗАЦИЯ*</span>
					<span v-if="currentCertificate.data.organization.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="text"
						placeholder="Название организации"
						:class="{ error: currentCertificate.errors.organization.status }"
						v-model="currentCertificate.data.organization.body"
						@input="currentCertificate.data.organization.edited = true"
						@blur="checkModalInput('currentCertificate', 'organization', 'text')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.organization.body">
						{{ currentCertificate.errors.organization.body }}
					</span>
				</template>
			</container-input-once>
			<!-- Дата окончания обучения -->
			<container-input-once :type="modalCertificates.type == 'create' ? 'create' : 'edit'">
				<template #title>
					<span>ОКОНЧАНИЕ ОБУЧЕНИЯ*</span>
					<span v-if="currentCertificate.data.endEducation.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="date"
						placeholder="Введите дату"
						:class="{ error: currentCertificate.errors.endEducation.status }"
						v-model="currentCertificate.data.endEducation.body"
						@blur="checkModalInput('currentCertificate', 'endEducation', 'text')"
						@input="currentCertificate.data.endEducation.edited = true"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.endEducation.body">
						{{ currentCertificate.errors.endEducation.body }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="addCertificate" v-if="modalCertificates.type == 'create'">
					Создать
				</button-claim>
				<button-default @click="updateCertificate" v-if="modalCertificates.type == 'edit'">
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>
	<!--____________________________________________________-->
	<!--4. Образования                                      -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalEducations')" :modal="modalEducations">
		<template #title>
			<span class="create" v-if="modalEducations.type == 'create'">
				ОБРАЗОВАНИЕ (СОЗДАНИЕ)
			</span>
			<span v-if="modalEducations.type == 'edit'">ОБРАЗОВАНИЕ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<container-input>
				<!-- Название -->
				<container-input-once :type="modalEducations.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span>НАЗВАНИЕ*</span>
						<span v-if="currentEducation.data.name.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите название"
							autocomplete="off"
							:class="{ error: currentEducation.errors.name.status }"
							v-model="currentEducation.data.name.body"
							@blur="checkModalInput('currentEducation', 'name', 'text')"
							@input="currentEducation.data.name.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.name.status">
							{{ currentEducation.errors.name.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Организация -->
				<container-textarea-once :type="modalEducations.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span :class="{ create: modalEducations.type == 'create' }">ОРГАНИЗАЦИЯ*</span>
						<span
							:class="{ create: modalEducations.type == 'create' }"
							v-if="currentEducation.data.organization.edited"
						>
							(ИЗМЕНЕНО)
						</span>
					</template>
					<template #textarea>
						<textarea
							rows="4"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: currentEducation.errors.organization.status }"
							v-model="currentEducation.data.organization.body"
							@blur="checkModalInput('currentEducation', 'organization', 'text')"
							@input="currentEducation.data.organization.edited = true"
						></textarea>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.organization.status">
							{{ currentEducation.errors.organization.body }}
						</span>
					</template>
				</container-textarea-once>
				<!-- Дата получения образования -->
				<container-input-once :type="modalEducations.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span :class="{ create: modalEducations.type == 'create' }">ДАТА ПОЛУЧЕНИЯ*</span>
						<span
							:class="{ create: modalEducations.type == 'create' }"
							v-if="currentEducation.data.date.edited"
						>
							(ИЗМЕНЕНО)</span
						>
					</template>
					<template #input>
						<input
							type="date"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: currentEducation.errors.date.status }"
							v-model="currentEducation.data.date.body"
							@blur="checkModalInput('currentEducation', 'date', 'text')"
							@input="currentEducation.data.date.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.date.status">
							{{ currentEducation.errors.date.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Выбор специализации -->
				<container-input-once :type="modalEducations.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span :class="{ create: modalEducations.type == 'create' }">СПЕЦИАЛИЗАЦИЯ*</span>
						<span
							:class="{ create: modalEducations.type == 'create' }"
							v-if="currentEducation.data.speсialization.edited"
						>
							(ИЗМЕНЕНО)</span
						>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите специализацию"
							autocomplete="off"
							list="eduacation-specializations"
							:class="{ error: currentEducation.errors.speсialization.status }"
							v-model="currentEducation.data.speсialization.body"
							@blur="checkModalInput('currentEducation', 'speсialization', 'text')"
							@input="currentEducation.data.speсialization.edited = true"
						/>
						<datalist id="eduacation-specializations">
							<option value="Лечебное дело"></option>
							<option value="Педиатрия"></option>
							<option value="Сестринское дело"></option>
							<option value="Фармация"></option>
						</datalist>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.speсialization.status">
							{{ currentEducation.errors.speсialization.body }}
						</span>
					</template>
				</container-input-once>
			</container-input>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="addEducation" v-if="modalEducations.type == 'create'">
					Создать
				</button-claim>
				<button-default @click="updateEducation" v-if="modalEducations.type == 'edit'">
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>
	<!--____________________________________________________-->
	<!--5. Прошлые работы                                   -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<admin-modal @touchCloseModal="closeModal('modalWorks')" :modal="modalWorks">
		<template #title>
			<span class="create" v-if="modalWorks.type == 'create'"> РАБОТА (СОЗДАНИЕ) </span>
			<span v-if="modalWorks.type == 'edit'">РАБОТА (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<container-input>
				<!-- Название -->
				<container-input-once :type="modalWorks.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span>НАЗВАНИЕ*</span>
						<span v-if="currentWork.data.name.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Название работы"
							v-model="currentWork.data.name.body"
							:class="{ error: currentWork.errors.name.status }"
							@input="currentWork.data.name.edited = true"
							@blur="checkModalInput('currentWork', 'name', 'text')"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentWork.errors.name.status">
							{{ currentWork.errors.name.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Организация -->
				<container-textarea-once :type="modalWorks.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span>ОРГАНИЗАЦИЯ*</span>
						<span v-if="currentWork.data.organization.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #textarea>
						<textarea
							rows="4"
							placeholder="Название организации"
							v-model="currentWork.data.organization.body"
							:class="{ error: currentWork.errors.organization.status }"
							@input="currentWork.data.organization.edited = true"
							@blur="checkModalInput('currentWork', 'organization', 'text')"
						></textarea>
					</template>
					<template #error>
						<span class="error" v-if="currentWork.errors.organization.status">
							{{ currentWork.errors.organization.body }}
						</span>
					</template>
				</container-textarea-once>
				<!-- Начало и конец -->
				<container-input-two
					:fieldset="true"
					:type="modalWorks.type == 'create' ? 'create' : 'default'"
				>
					<template #legend>
						<span>НАЧАЛО И ОКОНЧАНИЕ РАБОТЫ</span>
					</template>
					<!-- Начало работы -->
					<template #title-one>
						<span>ДАТА НАЧАЛА*</span>
						<span v-if="currentWork.data.startWork.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="date"
							autocomplete="off"
							v-model="currentWork.data.startWork.body"
							:class="{ error: currentWork.errors.startWork.status }"
							@input="currentWork.data.startWork.edited = true"
							@blur="checkModalInput('currentWork', 'startWork', 'text')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentWork.errors.startWork.status">
							{{ currentWork.errors.startWork.body }}
						</span>
					</template>
					<!-- Конец работы -->
					<template #title-two>
						<span>ДАТА ОКОНЧАНИЯ*</span>
						<span v-if="currentWork.data.endWork.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="date"
							placeholder="Введите улицу"
							autocomplete="off"
							v-model="currentWork.data.endWork.body"
							:class="{ error: currentWork.errors.endWork.status }"
							@input="currentWork.data.endWork.edited = true"
							@blur="checkModalInput('currentWork', 'endWork', 'text')"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentWork.errors.endWork.status">
							{{ currentWork.errors.endWork.body }}
						</span>
					</template>
				</container-input-two>
			</container-input>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="addWork" v-if="modalWorks.type == 'create'">
					Создать
				</button-claim>
				<button-default @click="updateWork" v-if="modalWorks.type == 'edit'">
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   СПЕЦИАЛИСТ                      |-->
	<!--|___________________________________________________|-->
	<!--____________________________________________________-->
	<!--1. Профиль                                          -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<!-- HACK Проработать детальнее адаптивный дизайн -->
	<!-- HACK Переделать функцию модульного сохранения строго под сохранение данных всей страницы -->
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists/{{ $route.params.id }}</template>
	</info-bar>

	<!-- Основные данные врача -->
	<block-once>
		<block-title>
			<template #title>
				<span :class="{ create: $route.params.id == 'new' }">ПРОФИЛЬ</span>
				<span v-if="$route.params.id == 'new'" class="create"> (СОЗДАНИЕ)</span>
			</template>
			<template #buttons>
				<icon-save
					:width="28"
					:height="28"
					@click="saveSpecialistModular('all')"
					v-if="$route.params.id !== 'new'"
				/>
				<icon-add :width="28" :height="28" @click="addSpecialist" v-else></icon-add>
			</template>
		</block-title>

		<div class="container-profile" v-show="loading.sections.profile">
			<div class="profile-image" v-if="$route.params.id == 'new'">
				<div
					class="item"
					:style="{
						backgroundImage: `url(/storage/default/image-none-default.png)`,
					}"
				></div>
			</div>
			<div class="profile-image" v-else>
				<div
					class="item"
					:style="{
						backgroundImage: `url(${specialist.profile.data.path.body})`,
					}"
				></div>
			</div>
			<div class="profile-info">
				<container-input-two
					:fieldset="true"
					:type="$route.params.id == 'new' ? 'create' : 'edit'"
				>
					<template #legend>
						<span :class="{ create: $route.params.id === 'new' }">АВАТАР И ССЫЛКА</span>
					</template>
					<template #title-one>
						<span>ФОТО ВРАЧА<span v-if="$route.params.id == 'new'">*</span></span>
						<span v-if="specialist.profile.data.filename.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							class="profile-file"
							type="file"
							autocomplete="off"
							ref="fileUpload"
							:class="{ error: specialist.profile.errors.file.status }"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="specialist.profile.errors.file.status">
							{{ specialist.profile.errors.file.body }}
						</span>
					</template>
					<template #title-two>
						<span>ССЫЛКА НА ПРОДОКТОРОВ*</span>
						<span v-if="specialist.profile.data.link.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите ссылку"
							autocomplete="off"
							v-model="specialist.profile.data.link.body"
							:class="{ error: specialist.profile.errors.link.status }"
							@input="specialist.profile.data.link.edited = true"
							@blur="checkSpecialistInput('link', 'text')"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="true">
							{{ specialist.profile.errors.link.body }}
						</span>
					</template>
				</container-input-two>
				<container-input-three
					:fieldset="true"
					:type="$route.params.id == 'new' ? 'create' : 'edit'"
				>
					<template #legend>
						<span :class="{ create: $route.params.id == 'new' }">Ф.И.О.</span>
					</template>
					<template #title-one>
						<span>ФАМИЛИЯ*</span>
						<span v-if="specialist.profile.data.family.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите фамилию"
							autocomplete="off"
							v-model="specialist.profile.data.family.body"
							:class="{ error: specialist.profile.errors.family.status }"
							@input="specialist.profile.data.family.edited = true"
							@blur="checkSpecialistInput('family', 'text')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="specialist.profile.errors.family.status">
							{{ specialist.profile.errors.family.body }}
						</span>
					</template>
					<template #title-two>
						<span>ИМЯ*</span>
						<span v-if="specialist.profile.data.name.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите имя"
							autocomplete="off"
							v-model="specialist.profile.data.name.body"
							:class="{ error: specialist.profile.errors.name.status }"
							@input="specialist.profile.data.name.edited = true"
							@blur="checkSpecialistInput('name', 'text')"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="specialist.profile.errors.name.status">
							{{ specialist.profile.errors.name.body }}
						</span>
					</template>
					<template #title-three>
						<span>ОТЧЕСТВО</span>
						<span v-if="specialist.profile.data.surname.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-three>
						<input
							type="text"
							placeholder="Введите отчество"
							autocomplete="off"
							v-model="specialist.profile.data.surname.body"
							:class="{ error: specialist.profile.errors.surname.status }"
							@input="specialist.profile.data.surname.edited = true"
						/>
					</template>
					<template #error-three>
						<span class="error" v-if="specialist.profile.errors.surname.status">
							{{ specialist.profile.errors.surname.body }}
						</span>
					</template>
				</container-input-three>
			</div>
		</div>
		<div class="container-profile-other" v-show="loading.sections.profile">
			<container-input>
				<container-select-three
					:fieldset="true"
					:type="$route.params.id == 'new' ? 'create' : 'edit'"
				>
					<template #legend>
						<span :class="{ create: $route.params.id === 'new' }">НАУЧНОЕ ОБРАЗОВАНИЕ</span>
					</template>
					<template #title-one>
						<span>КАТЕГОРИЯ*</span>
						<span v-if="specialist.profile.data.category.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #select-one>
						<select
							v-model="specialist.profile.data.category.body"
							:class="{ error: specialist.profile.errors.category.status }"
							@blur="checkSpecialistInput('category', 'select')"
						>
							<option value="" disabled>Ничего не выбрано...</option>
							<option value="Первая">Первая</option>
							<option value="Вторая">Вторая</option>
							<option value="Высшая">Высшая</option>
						</select>
					</template>
					<template #error-one>
						<span class="error" v-if="specialist.profile.errors.category.status">
							{{ specialist.profile.errors.category.body }}
						</span>
					</template>
					<template #title-two>
						<span>СТЕПЕНЬ</span>
						<span v-if="specialist.profile.data.degree.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #select-two>
						<input
							type="text"
							placeholder="Введите степень"
							autocomplete="off"
							list="specialist-dedgree"
							v-model="specialist.profile.data.degree.body"
							@input="specialist.profile.data.degree.edited = true"
						/>
						<datalist id="specialist-dedgree">
							<option value="Отсутствует">Отсутствует</option>
							<option value="Кандидат медицинских наук">Кандидат медицинских наук</option>
							<option value="Доктор медицинских наук">Доктор медицинских наук</option>
						</datalist>
					</template>
					<template #title-three>
						<span>ЗВАНИЕ</span>
						<span v-if="specialist.profile.data.rank.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #select-three>
						<input
							type="text"
							placeholder="Введите звание"
							autocomplete="off"
							list="specialist-rank"
							v-model="specialist.profile.data.rank.body"
							@input="specialist.profile.data.rank.edited = true"
						/>
						<datalist id="specialist-rank">
							<option value="Отсутствует">Отсутствует</option>
							<option value="Доцент">Доцент</option>
							<option value="Профессор">Профессор</option>
						</datalist>
					</template>
				</container-select-three>
				<!-- Первая работа -->
				<container-input-two
					:fieldset="true"
					:type="$route.params.id == 'new' ? 'create' : 'edit'"
				>
					<template #legend>
						<span :class="{ create: $route.params.id === 'new' }">НАЧАЛО КАРЪЕРЫ</span>
					</template>
					<template #title-one>
						<span>ДАТА*</span>
						<span v-if="specialist.profile.data.startWorkAge.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="date"
							autocomplete="off"
							v-model="specialist.profile.data.startWorkAge.body"
							:class="{ error: specialist.profile.errors.startWorkAge.status }"
							@input="specialist.profile.data.startWorkAge.edited = true"
							@blur="checkSpecialistInput('startWorkAge', 'text')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="specialist.profile.errors.startWorkAge.status">
							{{ specialist.profile.errors.startWorkAge.body }}
						</span>
					</template>
					<template #title-two>
						<span>ГОРОД</span>
						<span v-if="specialist.profile.data.startWorkCity.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите название города"
							autocomplete="off"
							v-model="specialist.profile.data.startWorkCity.body"
							@input="specialist.profile.data.startWorkCity.edited = true"
						/>
					</template>
				</container-input-two>
				<!-- Статус приёма -->
				<container-input-two-sub
					:fieldset="true"
					:subOne="false"
					:subTwo="specialist.profile.data.childrenDoctor.body ? true : false"
					:type="$route.params.id == 'new' ? 'create' : 'edit'"
				>
					<template #legend>
						<span :class="{ create: $route.params.id === 'new' }">ПРИЁМ ВРАЧА</span>
					</template>
					<template #title-one>
						<span>У ВЗРОСЛЫХ*</span>
						<span v-if="specialist.profile.data.adultDoctor.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<select
							v-model="specialist.profile.data.adultDoctor.body"
							:class="{ error: specialist.profile.errors.adultDoctor.status }"
							@blur="checkSpecialistInput('adultDoctor', 'select')"
						>
							<option value="" disabled>Ничего не выбрано...</option>
							<option :value="0">Нет</option>
							<option :value="1">Да</option>
						</select>
					</template>
					<template #error-one>
						<span class="error" v-if="specialist.profile.errors.adultDoctor.status">
							{{ specialist.profile.errors.adultDoctor.body }}
						</span>
					</template>
					<template #title-two>
						<span>У ДЕТЕЙ*</span>
						<span v-if="specialist.profile.data.childrenDoctor.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<select
							autocomplete="off"
							v-model="specialist.profile.data.childrenDoctor.body"
							:class="{ error: specialist.profile.errors.childrenDoctor.status }"
							@blur="checkSpecialistInput('childrenDoctor', 'select')"
						>
							<option value="" disabled>Ничего не выбрано...</option>
							<option :value="0">Нет</option>
							<option :value="1">Да</option>
						</select>
					</template>
					<template #error-two>
						<span class="error" v-if="specialist.profile.errors.childrenDoctor.status">
							{{ specialist.profile.errors.childrenDoctor.body }}
						</span>
					</template>
					<template #title-sub-two>
						<span>ВОЗРАСТ* (+{{ specialist.profile.data.childrenDoctorAge.body }})</span>
						<span v-if="specialist.profile.data.childrenDoctorAge.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-sub-two>
						<input
							type="number"
							placeholder="Годы"
							v-model="specialist.profile.data.childrenDoctorAge.body"
							:class="{ error: specialist.profile.errors.childrenDoctorAge.status }"
							@input="specialist.profile.data.childrenDoctorAge.edited = true"
							@blur="checkSpecialistInput('childrenDoctorAge', 'number')"
						/>
					</template>
					<template #error-sub-two>
						<span class="error" v-if="specialist.profile.errors.childrenDoctorAge.status">
							{{ specialist.profile.errors.childrenDoctorAge.body }}
						</span>
					</template>
				</container-input-two-sub>
			</container-input>
		</div>

		<!-- Загрузчик профиля -->
		<loader-child
			:isLoading="loading.loader.profile"
			:minHeight="600"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block-once>

	<block-two v-if="$route.params.id !== 'new'">
		<!--____________________________________________________-->
		<!--2. Специализации                                    -->
		<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
		<template #title-one>
			<block-title>
				<template #title>
					СПЕЦИАЛИЗАЦИИ
					<span v-show="loading.sections.specializations">
						({{ specialist.connections.specializations.length }})
					</span>
				</template>
				<template #buttons>
					<icon-save
						:width="28"
						:height="28"
						@click="saveSpecialistModular('specializations')"
						v-if="false"
					/>
				</template>
			</block-title>
		</template>
		<template #body-one>
			<div class="profile-list" v-show="loading.sections.specializations">
				<!-- Если специализации не выбраны -->
				<div class="item-empty" v-if="specialist.connections.specializations.length == 0">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Если специализации выбраны -->
				<template v-else>
					<div class="item">
						<div>Название</div>
					</div>
					<!-- HACK Сделать сортировку по алфавиту -->
					<div
						class="item"
						v-for="specialization in sortedConnectionsSpecializations"
						:key="specialization.id"
					>
						<!-- <div class="item-title">1</div> -->
						<div class="item-title">
							{{
								sections.specializations.filter(
									(item) => item.id == specialization.id_specialization
								)[0].name
							}}
						</div>
						<div class="item-priem"></div>
						<div
							class="item-close"
							@click="removeArrValue('specializations', specialization)"
						>
							<icon-close :width="26" :height="26" />
						</div>
					</div>
				</template>
			</div>

			<!-- Загрузчик специализаций -->
			<loader-child
				:isLoading="loading.loader.specializations"
				:minHeight="100"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>

			<block-buttons>
				<button-default @click="editSpecialization" v-if="$route.params.id !== 'new'">
					Добавить
				</button-default>
				<button-claim @click="editSpecialization" v-else> Добавить </button-claim>
			</block-buttons>
		</template>
		<!--____________________________________________________-->
		<!--3. Клиники                                          -->
		<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
		<template #title-two>
			<block-title>
				<template #title>
					КЛИНИКИ
					<span v-show="loading.sections.clinics">
						({{ specialist.connections.clinics.length }})
					</span>
				</template>
				<template #buttons>
					<icon-save
						:width="28"
						:height="28"
						@click="saveSpecialistModular('clinics')"
						v-if="false"
					/>
				</template>
			</block-title>
		</template>
		<template #body-two>
			<div class="profile-list" v-show="loading.sections.clinics">
				<!-- Если клиники не выбраны -->
				<div class="item-empty" v-if="specialist.connections.clinics.length == 0">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<template v-else>
					<div class="item">
						<div>Название</div>
						<div>Приём</div>
						<div></div>
					</div>
					<!-- Если клиники выбраны -->
					<div class="item" v-for="clinic in specialist.connections.clinics" :key="clinic.id">
						<div class="item-title">
							{{ sections.clinics.filter((item) => item.id == clinic.id_clinic)[0].name }}
						</div>
						<div class="item-priem">
							{{ clinic.priem ? `Да` : "Нет" }}
						</div>
						<div class="item-close" @click="removeArrValue('clinics', clinic)">
							<icon-close :width="26" :height="26" />
						</div>
					</div>
				</template>
			</div>

			<!-- Загрузчик клиник -->
			<loader-child
				:isLoading="loading.loader.clinics"
				:minHeight="100"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>

			<block-buttons>
				<button-default @click="editClinics" v-if="$route.params.id !== 'new'">
					Добавить
				</button-default>
			</block-buttons>
		</template>
	</block-two>

	<!--____________________________________________________-->
	<!--4. Сертификаты                                      -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<block-once v-if="$route.params.id !== 'new'">
		<block-title>
			<template #title>
				СЕРТИФИКАТЫ
				<span v-show="loading.sections.certificates">
					({{ specialist.connections.certificates.length }})
				</span>
			</template>
			<template #buttons>
				<icon-save
					:width="28"
					:height="28"
					@click="saveSpecialistModular('certificates')"
					v-if="false"
				/>
			</template>
		</block-title>

		<admin-specialists-table
			v-show="loading.sections.certificates"
			:array="specialist.connections.certificates"
			@useFilter="filterArray"
			@touchEditArrValue="editArrayValue('edit', 'certificates', $event)"
			@touchRemoveArrValue="updateDeleteValue('certificates', $event)"
		/>

		<!-- Загрузчик профиля -->
		<loader-child
			:isLoading="loading.loader.clinics"
			:minHeight="100"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="editArrayValue('create', 'certificates', null)">
				Добавить
			</button-default>
		</block-buttons>
	</block-once>
	<!--____________________________________________________-->
	<!--5. Образование                                      -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<block-once v-if="$route.params.id !== 'new'">
		<block-title>
			<template #title>
				ОБРАЗОВАНИЕ
				<span v-show="loading.sections.educations">
					({{ specialist.connections.educations.length }})
				</span>
			</template>
			<template #buttons>
				<icon-save
					:width="28"
					:height="28"
					@click="saveSpecialistModular('educations')"
					v-if="false"
				/>
			</template>
		</block-title>

		<admin-specialists-table
			v-show="loading.sections.educations"
			:array="getSpecialistEducations"
			@touchEditArrValue="editArrayValue('edit', 'educations', $event)"
			@touchRemoveArrValue="updateDeleteValue('educations', $event)"
		/>

		<!-- Загрузчик профиля -->
		<loader-child
			:isLoading="loading.loader.clinics"
			:minHeight="100"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="editArrayValue('create', 'educations', null)">
				Добавить
			</button-default>
		</block-buttons>
	</block-once>

	<!--____________________________________________________-->
	<!--6. Места работы                                     -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<block-once v-if="$route.params.id !== 'new'">
		<block-title>
			<template #title>
				МЕСТА РАБОТЫ
				<span v-show="loading.sections.works">
					({{ specialist.connections.works.length }})
				</span>
			</template>
			<template #buttons>
				<icon-save
					:width="28"
					:height="28"
					@click="saveSpecialistModular('works')"
					v-if="false"
				/>
			</template>
		</block-title>

		<admin-specialists-table
			v-show="loading.sections.works"
			:array="getSpecialistWorks"
			@touchEditArrValue="editArrayValue('edit', 'works', $event)"
			@touchRemoveArrValue="updateDeleteValue('works', $event)"
		/>

		<!-- Загрузчик профиля -->
		<loader-child
			:isLoading="loading.loader.clinics"
			:minHeight="100"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="editArrayValue('create', 'works', null)">
				Добавить
			</button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import TableButtonDefault from "../../../components/ui/admin/tables/TableButtonDefault.vue";
import TableButtonRemove from "../../../components/ui/admin/tables/TableButtonRemove.vue";

import IconClose from "../../../components/icons/IconClose.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputTwoSub from "../../../components/ui/admin/containers/input/ContainerInputTwoSub.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";
import ContainerSelectThree from "../../../components/ui/admin/containers/select/ContainerSelectThree.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import Pagination from "../../../components/ui/admin/pagination/Pagination.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import IconSave from "../../../components/icons/IconSave.vue";
import IconSaveAll from "../../../components/icons/IconSaveAll.vue";
import IconAdd from "../../../components/icons/IconAdd.vue";

import axios from "axios";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
		AdminModal,
		AdminSpecialistsTable,
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		BlockOnce,
		BlockTwo,
		BlockTitle,
		BlockButtons,
		IconClose,
		SpanError,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputTwoSub,
		ContainerInputThree,
		ContainerSelectOnce,
		ContainerSelectThree,
		ContainerTextareaOnce,
		Pagination,
		TableButtonDefault,
		TableButtonRemove,
		ButtonDefault,
		ButtonRemove,
		ButtonClaim,
		IconSave,
		IconSaveAll,
		IconAdd,
		axios,
		RouterView,
		RouterLink,
	},
	data() {
		return {
			/* Модальные окна */
			modalSpecializations: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			modalClinics: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			modalCertificates: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			currentCertificate: {
				errors: {
					id: {
						body: "",
						status: false,
					},
					organization: {
						body: "",
						status: false,
					},
					endEducation: {
						body: "",
						status: false,
					},
					name: {
						body: "",
						status: false,
					},
				},
				data: {
					id: {
						body: "",
						edited: false,
					},
					organization: {
						body: "",
						edited: false,
					},
					endEducation: {
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					create: {
						body: false,
						edited: false,
					},
					delete: {
						body: false,
						edited: false,
					},
				},
			},
			modalEducations: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			currentEducation: {
				errors: {
					id: {
						body: null,
						status: false,
					},
					name: {
						body: null,
						status: false,
					},
					organization: {
						body: null,
						status: false,
					},
					date: {
						body: null,
						status: false,
					},
					speсialization: {
						body: null,
						status: false,
					},
					create: {
						body: false,
						status: false,
					},
					delete: {
						body: false,
						status: false,
					},
				},
				data: {
					id: {
						body: null,
						edited: false,
					},
					name: {
						body: null,
						edited: false,
					},
					organization: {
						body: null,
						edited: false,
					},
					date: {
						body: null,
						edited: false,
					},
					speсialization: {
						body: null,
						edited: false,
					},
					create: {
						body: false,
						edited: false,
					},
					delete: {
						body: false,
						edited: false,
					},
				},
			},
			modalWorks: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			currentWork: {
				errors: {
					id: {
						body: "",
						status: false,
					},
					startWork: {
						body: "",
						status: false,
					},
					endWork: {
						body: "",
						status: false,
					},
					organization: {
						body: "",
						status: false,
					},
					name: {
						body: "",
						status: false,
					},
				},
				data: {
					id: {
						body: "",
						edited: false,
					},
					startWork: {
						body: "",
						edited: false,
					},
					endWork: {
						body: "",
						edited: false,
					},
					organization: {
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					create: {
						body: false,
						edited: false,
					},
					delete: {
						body: false,
						edited: false,
					},
				},
			},
			/* Загрузчик */
			loading: {
				loader: {
					profile: true,
					certificates: true,
					specializations: true,
					clinics: true,
					educations: true,
					works: true,
				},
				sections: {
					profile: false,
					certificates: false,
					specializations: false,
					clinics: false,
					educations: false,
					works: false,
				},
			},
			/* Пагинация */
			paginationSpecializations: {
				pages: {
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
				},
			},
			paginationClinics: {
				pages: {
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
				},
			},
			/* Специалист */
			specialist: {
				// Основная информация
				profile: {
					data: {
						id: {
							body: "",
							edited: false,
						},
						link: {
							body: "",
							edited: false,
						},
						family: {
							body: "",
							edited: false,
						},
						name: {
							body: "",
							edited: false,
						},
						surname: {
							body: "",
							edited: false,
						},
						category: {
							body: "",
							edited: false,
						},
						degree: {
							body: "",
							edited: false,
						},
						rank: {
							body: "",
							edited: false,
						},
						startWorkAge: {
							body: "",
							edited: false,
						},
						startWorkCity: {
							body: "",
							edited: false,
						},
						adultDoctor: {
							body: "",
							edited: false,
						},
						childrenDoctor: {
							body: "",
							edited: false,
						},
						childrenDoctorAge: {
							body: "",
							edited: false,
						},
						hide: {
							body: false,
							edited: false,
						},
						filename: {
							body: "",
							edited: false,
						},
						path: {
							body: "",
							edited: false,
						},
					},
					errors: {
						file: {
							body: "",
							status: false,
						},
						id: {
							body: "",
							status: false,
						},
						link: {
							body: "",
							status: false,
						},
						family: {
							body: "",
							status: false,
						},
						name: {
							body: "",
							status: false,
						},
						surname: {
							body: "",
							status: false,
						},
						category: {
							body: "",
							status: false,
						},
						degree: {
							body: "",
							status: false,
						},
						rank: {
							body: "",
							status: false,
						},
						startWorkAge: {
							body: "",
							status: false,
						},
						startWorkCity: {
							body: "",
							status: false,
						},
						adultDoctor: {
							body: "",
							status: false,
						},
						childrenDoctor: {
							body: "",
							status: false,
						},
						childrenDoctorAge: {
							body: "",
							status: false,
						},
						hide: {
							body: "",
							status: false,
						},
						filename: {
							body: "",
							status: false,
						},
						path: {
							body: "",
							status: false,
						},
					},
				},
				// Связи
				connections: {
					certificates: [],
					specializations: [],
					clinics: [],
					educations: [],
					works: [],
				},
			},
			/* Секции */
			sections: {
				specializations: [],
				clinics: [],
			},
			/* Чекбоксовые массивы */
			cheked: {
				counter: 0,
				specializations: [],
				specializationsCategory: [],
				clinics: [],
			},
		};
	},
	computed: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ПРОФИЛЬ                       |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Специализации                                     */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* TODO Доделать сотрировку по алфавиту в блоке this.specialist.connections.specializations */
		sortedConnectionsSpecializations() {
			const collator = new Intl.Collator("ru");

			return [...this.specialist.connections.specializations].sort((a, b) => {
				return collator.compare(a.name, b.name);
			});
		},
		getPagesSpecializationsTotal() {
			return Math.ceil(
				this.sections.specializations.length / this.paginationSpecializations.elements.range
			);
		},
		/* HACK Сделать сортировку по алфавиту */
		getSortedSpecializations() {
			return [...this.sections.specializations].splice(
				(this.paginationSpecializations.pages.current - 1) *
					this.paginationSpecializations.elements.range,
				this.paginationSpecializations.elements.range
			);
		},
		/* _____________________________________________________*/
		/* 2. Клиники                                           */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		getPagesClinicsTotal() {
			return Math.ceil(this.sections.clinics.length / this.paginationClinics.elements.range);
		},
		/* HACK Сделать сортировку по алфавиту */
		getSortedClinics() {
			return [...this.sections.clinics].splice(
				(this.paginationClinics.pages.current - 1) * this.paginationClinics.elements.range,
				this.paginationClinics.elements.range
			);
		},
		/* _____________________________________________________*/
		/* 3. Сертификаты                                       */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* .... */
		/* _____________________________________________________*/
		/* 4. Обучения                                          */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		getSpecialistEducations() {
			return [...this.specialist.connections.educations];
		},
		/* _____________________________________________________*/
		/* 5. Прошлые работы                                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		getSpecialistWorks() {
			return [...this.specialist.connections.works];
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			if (!this.loading.loader.profile) {
				for (let key in this.loading.sections) {
					this.loading.sections[key] = true;
				}
			}
		},
		/* Получить название сертификата */
		getCertificateName(id) {
			let certificate = this.sections.certificates.filter((item) => {
				if (item.id == id.id_certificate) {
					return item;
				}
			});
			return certificate[0].name;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type, modalName, currentName) {
			if (modalName !== "modalSpecializations" && modalName !== "modalClinics") {
				this.clearModal(currentName, {
					errors: true,
					edited: true,
				});
			}

			switch (type) {
				case "create":
					{
						if (modalName !== "modalSpecializations" && modalName !== "modalClinics") {
							this.clearModal(currentName, {
								data: true,
							});
						}
						this[modalName].type = "create";
						this[modalName].status = true;
						this[modalName].style.create = true;
						this[modalName].style.delete = false;
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this[modalName].type = "edit";
						this[modalName].status = true;
						this[modalName].style.create = false;
						this[modalName].style.delete = false;
					}
					document.body.classList.add("modal-open");
					break;
				default:
					{
						let debbugStory = {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
					break;
			}
		},
		/* Закрытие */
		closeModal(modalName) {
			this[modalName].status = false;
			document.body.classList.remove("modal-open");
		},
		/* Очистка модального окна */
		clearModal(currentName, settings = { data: false, errors: false, edited: false }) {
			if (settings.data) {
				for (let key in this[currentName].data) {
					this[currentName].data[key].body = "";
				}
			}

			if (settings.edited) {
				for (let key in this[currentName].data) {
					this[currentName].data[key].edited = "";
				}
			}

			if (settings.errors) {
				for (let key in this[currentName].errors) {
					this[currentName].errors[key].body = "";
					this[currentName].errors[key].status = false;
				}
			}
		},
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка введенного текстового значения
		checkInputText(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value != "string") {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		// Проверка введенного текстового значения
		checkInputNumber(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}
			/* Проверка на соответствие типу Number */
			if (Number.isNaN(Number(value))) {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		checkSelect(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === "null") {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		// Проверка поля имени
		checkModalInput(currentName, dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = this.checkInputText(this[currentName].data[dataKey].body);
					break;
				case "number":
					errorLog = this.checkInputNumber(this[currentName].data[dataKey].body);
					break;
				case "select":
					errorLog = this.checkSelect(this[currentName].data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this[currentName].errors[dataKey].body = errorLog.message;
				this[currentName].errors[dataKey].status = true;

				return true;
			} else {
				this[currentName].errors[dataKey].body = "";
				this[currentName].errors[dataKey].status = false;

				return false;
			}
		},
		// Проверка всех полей ввода модального окна
		checkModalInputsAll(currentName, inputKeys) {
			let errorCount = 0;
			for (let i = 0; i < inputKeys.length; i++) {
				switch (inputKeys[i]) {
					// Для всех остальных полей
					default:
						if (this.checkModalInput(currentName, inputKeys[i], "text")) {
							errorCount++;
						}
						break;
				}
			}

			if (errorCount > 0) {
				return true;
			} else {
				return false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                РАБОТА С ПОЛЯМИ ВВОДА              |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Специалист                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка конкретного поля ввода
		checkSpecialistInput(dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = this.checkInputText(this.specialist.profile.data[dataKey].body);
					break;
				case "number":
					errorLog = this.checkInputNumber(this.specialist.profile.data[dataKey].body);
					break;
				case "select":
					errorLog = this.checkSelect(this.specialist.profile.data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.specialist.profile.errors[dataKey].body = errorLog.message;
				this.specialist.profile.errors[dataKey].status = true;

				return true;
			} else {
				this.specialist.profile.errors[dataKey].body = "";
				this.specialist.profile.errors[dataKey].status = false;

				return false;
			}
		},
		/* Проверка всех полей ввода */
		checkSpecialistInputsAll(inputKeys) {
			let errorCount = 0;
			for (let i = 0; i < inputKeys.length; i++) {
				switch (inputKeys[i]) {
					case "file":
						/* Проверка на тип загруженного файла */
						if (this.$refs.fileUpload.files[0].type !== "image/png") {
							this.specialist.profile.errors.file.status = true;
							this.specialist.profile.errors.file.body = "Разрешенный формат файла: png.";
							errorCount++;

							continue;
						}

						/* Проверка на размер загруженного файла */
						let fileSize = this.$refs.fileUpload.files[0].size / 1024 / 1024;
						if (fileSize > 10) {
							this.specialist.profile.errors.file.status = true;
							this.specialist.profile.errors.file.body = "Размер файла более 10 МБ.";
							errorCount++;

							continue;
						}

						this.specialist.profile.errors.file.status = false;

						break;
					// Для индекса
					case "category":
					case "adultDoctor":
					case "childrenDoctor":
						if (this.checkSpecialistInput(inputKeys[i], "select")) {
							errorCount++;
						}
						break;
					// Для возраста
					case "childrenDoctorAge":
						if (this.checkSpecialistInput(inputKeys[i], "number")) {
							errorCount++;
						}
						break;
					// Для всех остальных полей
					default:
						if (this.checkSpecialistInput(inputKeys[i], "text")) {
							errorCount++;
						}
						break;
				}
			}

			if (errorCount > 0) {
				return true;
			} else {
				return false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    ПАГИНАЦИЯ                      |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Изменение текущей страницы */
		changePageSpecializations(pageNumber) {
			// Проверка на превышение количества страниц
			if (
				pageNumber >
				Math.ceil(
					this.sections.specializations.length / this.paginationSpecializations.elements.range
				)
			) {
				return;
			} else if (pageNumber < 1) {
				return;
			}

			this.paginationSpecializations.pages.current = pageNumber;
		},
		/* Изменение текущей страницы */
		changePageClinics(pageNumber) {
			// Проверка на превышение количества страниц
			if (
				pageNumber >
				Math.ceil(this.sections.clinics.length / this.paginationClinics.elements.range)
			) {
				return;
			} else if (pageNumber < 1) {
				return;
			}

			this.paginationClinics.pages.current = pageNumber;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                       ВРАЧ                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Профиль                                           */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Модульное сохранение данных */
		async saveSpecialistModular(type) {
			// Проверка на статус добавления специалиста
			if (this.specialist.profile.data.id.body === "new") return;

			switch (type) {
				case "profile":
					this.saveProfileChanges();
					break;
				case "specializations":
					this.saveSpecializationsChanges();
					break;
				case "clinics":
					this.saveClinicsChanges();
					break;
				case "certificates":
					this.saveCertificateChanges();
					break;
				case "educations":
					this.saveEducationChanges();
					break;
				case "works":
					this.saveWorksChanges();
					break;
				case "all":
					this.saveSpecialistAll();
					break;
				default:
					break;
			}
		},
		/* Сохранение данных профиля */
		async saveProfileChanges() {
			try {
				if (
					this.checkSpecialistInputsAll([
						"link",
						"family",
						"name",
						"category",
						"adultDoctor",
						"childrenDoctor",
					])
				)
					return;

				if (this.specialist.profile.data.childrenDoctor.body) {
					if (this.checkSpecialistInputsAll(["childrenDoctorAge"])) return;
				} else {
					this.specialist.profile.errors.childrenDoctorAge.status = false;
					this.specialist.profile.errors.childrenDoctorAge.body = "";
				}

				if (this.$refs.fileUpload.files[0]) {
					if (this.checkSpecialistInputsAll(["file"])) return;
				}

				let formData = new FormData();
				formData.append("type", "profile");
				formData.append("image", this.$refs.fileUpload.files[0]);
				formData.append("profile", JSON.stringify(this.specialist.profile.data));

				// Сохранение данных
				axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `save-specialist-modular`,
					headers: {
						Accept: "multipart/form-data",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: formData,
				})
					.then((response) => {
						this.clearSpecialistProfileEdited();
						this.$refs.fileUpload.value = "";

						if (response.data.status) {
							if (response.data.data) {
								this.specialist.profile.data.path.body = response.data.data;
								this.specialist.profile.data.filename.body = response.data.data.replace(
									"/storage/specialists/",
									""
								);
							}

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} else {
							let debbugStory = {
								title: "Ошибка.",
								body: response.data.message,
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					})
					.catch((error) => {
						console.log(error);
					});
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось сохранить данные",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Сохранение данных профиля */
		async addSpecialist() {
			// Проверка на статус добавления специалиста
			if (this.specialist.profile.data.id.body !== "new") return;

			if (!this.$refs.fileUpload.files[0]) {
				this.specialist.profile.errors.file.status = true;
				this.specialist.profile.errors.file.body = "Файл не загружен.";

				// Проверка на заполненность обязательных полей
				if (
					this.checkSpecialistInputsAll([
						"link",
						"family",
						"name",
						"category",
						"adultDoctor",
						"childrenDoctor",
					])
				)
					return;
			} else {
				// Проверка на заполненность обязательных полей
				if (
					this.checkSpecialistInputsAll([
						"link",
						"family",
						"name",
						"category",
						"adultDoctor",
						"childrenDoctor",
						"file",
					])
				)
					return;

				if (this.specialist.profile.data.childrenDoctor.body) {
					if (this.checkSpecialistInputsAll(["childrenDoctorAge"])) return;
				} else {
					this.specialist.profile.errors.childrenDoctorAge.status = false;
					this.specialist.profile.errors.childrenDoctorAge.body = "";
				}

				let formData = new FormData();
				formData.append("image", this.$refs.fileUpload.files[0]);
				formData.append("profile", JSON.stringify(this.specialist.profile.data));

				// Сохранение данных
				await axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `add-specialist`,
					headers: {
						Accept: "multipart/form-data",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: formData,
				})
					.then((response) => {
						this.clearSpecialistProfileEdited();
						this.$refs.fileUpload.value = "";

						if (response.data.status) {
							this.specialist.profile.data.id.body = response.data.data.id;

							this.specialist.profile.data.path.body = response.data.data.path;
							this.specialist.profile.data.filename.body = response.data.data.path.replace(
								"/storage/specialists/",
								""
							);

							this.$router.push(String(response.data.data.id));

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} else {
							let debbugStory = {
								title: "Ошибка.",
								body: response.data.message,
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					})
					.catch((error) => {
						console.log(error);
					});
			}
		},
		async saveSpecialistAll() {
			if (
				this.checkSpecialistInputsAll([
					"link",
					"family",
					"name",
					"category",
					"adultDoctor",
					"childrenDoctor",
					"startWorkAge",
				])
			)
				return;

			if (this.specialist.profile.data.childrenDoctor.body) {
				if (this.checkSpecialistInputsAll(["childrenDoctorAge"])) return;
			} else {
				this.specialist.profile.errors.childrenDoctorAge.status = false;
				this.specialist.profile.errors.childrenDoctorAge.body = "";
			}

			if (this.$refs.fileUpload.files[0]) {
				if (this.checkSpecialistInputsAll(["file"])) return;
			}

			try {
				let formData = new FormData();
				formData.append("type", "all");
				// Данные блока профиля
				formData.append("image", this.$refs.fileUpload.files[0]);
				formData.append("profile", JSON.stringify(this.specialist.profile.data));
				// Id специалиста
				formData.append("id", JSON.stringify(this.specialist.profile.data.id.body));
				// Данные блока специализаций
				formData.append(
					"specializations",
					JSON.stringify(this.specialist.connections.specializations)
				);
				// Данные блока клиник
				formData.append("clinics", JSON.stringify(this.specialist.connections.clinics));
				// Данные блока сертификаты
				formData.append(
					"certificates",
					JSON.stringify(this.specialist.connections.certificates)
				);
				// Данные блока образования
				formData.append("educations", JSON.stringify(this.specialist.connections.educations));
				// Данные блока прошлых работ
				formData.append("works", JSON.stringify(this.specialist.connections.works));

				// Сохранение данных
				await axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `save-specialist-modular`,
					headers: {
						Accept: "multipart/form-data",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: formData,
				})
					.then((response) => {
						if (response.data.status) {
							this.clearSpecialistProfileEdited();

							// Замена изображения профиля
							if (response.data.data.imagePath != null) {
								this.clearSpecialistProfileEdited();
								this.$refs.fileUpload.value = "";

								this.specialist.profile.data.path.body = response.data.data.imagePath;
								this.specialist.profile.data.filename.body =
									response.data.data.imagePath.replace("/storage/specialists/", "");
							}

							let blocks = ["certificates", "educations", "works"];

							blocks.forEach((block) => {
								/* Обновление id в соответствии с изменениями */
								this.updateIdFromConnection(block, response.data.data[block]);

								/* Очистка удалённых элементов */
								this.clearDeletesFromConnection(block);

								/* Обновление флагов на удаление и сохранение */
								this.clearFlagsFromConnection(block);
							});

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} else {
							let debbugStory = {
								title: "Ошибка.",
								body: response.data.message,
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					})
					.catch((error) => {
						console.log(error);
					});
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При сохранении данных специалиста произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Очистка статуса изменений */
		clearSpecialistProfileEdited() {
			for (let key in this.specialist.profile.data) {
				this.specialist.profile.data[key].edited = false;
			}
		},
		/* _____________________________________________________*/
		/* 2. Специализации                                     */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие списка специализация */
		editSpecialization() {
			// Обнуление выбранных специализаций
			this.cheked.specializations = [];

			// Заполнение выбранных специализаций
			this.specialist.connections.specializations.forEach((connSpecilization) =>
				this.cheked.specializations.push(connSpecilization.id_specialization)
			);

			this.openModal("edit", "modalSpecializations", null);
		},
		/* Обновление специализаций */
		updateSpecialization() {
			try {
				// Обнуление сертификатов специалиста
				this.specialist.connections.specializations = [];

				let maxId = 0;
				// Если ничего не выбрано, то оставляем пустой массив, иначе заполняем массив выбранными значениями
				if (this.cheked.specializations.length !== 0) {
					this.cheked.specializations.sort((a, b) => {
						if (a > b) {
							return 1;
						} else if (a < b) {
							return -1;
						} else {
							return 0;
						}
					});

					/* Заполнение выбранных специализаций в массив */
					this.cheked.specializations.forEach((item) => {
						this.specialist.connections.specializations.push({
							id: maxId + 1,
							id_specialist:
								this.$route.params.id !== "new"
									? this.specialist.profile.data.id.body
									: "new",
							id_specialization: item,
						});

						/* Поиск максимального id */
						this.specialist.connections.specializations.filter((item) => {
							if (item.id > maxId) maxId++;
						});
					});
				}
				this.closeModal("modalSpecializations");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При обновлении значений произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Сохранение изменений */
		async saveSpecializationsChanges() {
			try {
				let formData = new FormData();
				formData.append("type", "specializations");
				formData.append("id", JSON.stringify(this.specialist.profile.data.id.body));
				formData.append(
					"specializations",
					JSON.stringify(this.specialist.connections.specializations)
				);

				await axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `save-specialist-modular`,
					headers: {
						Accept: "multipart/form-data",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: formData,
				})
					.then((response) => {
						if (response.data.status) {
							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} else {
							let debbugStory = {
								title: "Ошибка.",
								body: response.data.message,
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					})
					.catch((error) => {
						let debbugStory = {
							title: "Ошибка.",
							body: "Данные почему-то не сохранились...",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					});
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При сохранении значений произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* _____________________________________________________*/
		/* 3. Клиники                                           */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие списка клиник */
		editClinics() {
			try {
				// Проверка на статус добавления специалиста
				if (this.specialist.profile.data.id.body === "new") return;

				// Обнуление массива
				this.cheked.clinics = [];

				// Заполнение выбранных клиник из массива
				this.specialist.connections.clinics.forEach((connClinic) => {
					this.cheked.clinics.push(connClinic.id_clinic);

					/* Заполнение статуса приёма в клиниках в массив */
					this.sections.clinics.find((clinic) => {
						return clinic.id == connClinic.id_clinic;
					}).priem = connClinic.priem;
				});

				this.openModal("edit", "modalClinics", null);
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При открытии списка произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Обновление клиник */
		updateClinics() {
			try {
				// Обнуление сертификатов специалиста
				this.specialist.connections.clinics = [];

				let maxId = 0;
				// Если ничего не выбрано, то оставляем пустой массив, иначе заполняем массив выбранными значениями
				if (this.cheked.clinics.length !== 0) {
					this.cheked.clinics.sort((a, b) => {
						if (a > b) {
							return 1;
						} else if (a < b) {
							return -1;
						} else {
							return 0;
						}
					});

					// Заполнение выбранных специализаций в массив
					this.cheked.clinics.forEach((checkClinic) => {
						this.specialist.connections.clinics.push({
							id: maxId + 1,
							priem: this.sections.clinics.find((clinic) => {
								return clinic.id == checkClinic;
							}).priem,
							id_specialist:
								this.$route.params.id !== "new"
									? this.specialist.profile.data.id.body
									: "new",
							id_clinic: checkClinic,
						});

						// Поиск максимального id
						this.specialist.connections.clinics.filter((connClinic) => {
							if (connClinic.id > maxId) maxId++;
						});
					});
				}

				this.closeModal("modalClinics");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При обновлении значений произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Сохранение изменений */
		async saveClinicsChanges() {
			try {
				let formData = new FormData();
				formData.append("type", "clinics");
				formData.append("id", JSON.stringify(this.specialist.profile.data.id.body));
				formData.append("clinics", JSON.stringify(this.specialist.connections.clinics));

				await axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `save-specialist-modular`,
					headers: {
						Accept: "multipart/form-data",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: formData,
				})
					.then((response) => {
						if (response.data.status) {
							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} else {
							let debbugStory = {
								title: "Ошибка.",
								body: response.data.message,
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					})
					.catch((error) => {
						let debbugStory = {
							title: "Ошибка.",
							body: "Данные почему-то не сохранились...",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					});
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При сохранении значений произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* _____________________________________________________*/
		/* 4. Сертификаты                                       */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Добавление данных */
		addCertificate() {
			if (
				this.checkModalInputsAll("currentCertificate", ["name", "organization", "endEducation"])
			)
				return;
			try {
				// Поиск максимального id
				let maxId = 0;
				this.specialist.connections.certificates.forEach((item) => {
					if (item.id > maxId) maxId = item.id;
				});
				this.specialist.connections.certificates.push({
					id: maxId + 1,
					name: this.currentCertificate.data.name.body,
					organization: this.currentCertificate.data.organization.body,
					endEducation: this.currentCertificate.data.endEducation.body,
					create: true,
					delete: false,
				});
				this.closeModal("modalCertificates");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При добавлении что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Обновление данных */
		updateCertificate() {
			if (
				this.checkModalInputsAll("currentCertificate", ["name", "organization", "endEducation"])
			)
				return;
			try {
				let сertificateToUpdate = this.specialist.connections.certificates.filter(
					(сertificate) => {
						if (сertificate.id === this.currentCertificate.data.id.body) {
							return сertificate;
						}
					}
				);
				for (let key in this.currentCertificate.data) {
					сertificateToUpdate[0][key] = this.currentCertificate.data[key].body;
				}
				this.closeModal("modalCertificates");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При обновлении что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Сохранение изменений */
		async saveCertificateChanges() {
			let formData = new FormData();
			formData.append("type", "certificates");
			formData.append("id", JSON.stringify(this.specialist.profile.data.id.body));
			formData.append("certificates", JSON.stringify(this.specialist.connections.certificates));

			await axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-specialist-modular`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							/* Обновление id в соответствии с изменениями */
							this.updateIdFromConnection("certificates", response.data.data);

							/* Очистка удалённых элементов */
							this.clearDeletesFromConnection("certificates");

							/* Обновление флагов на удаление и сохранение */
							this.clearFlagsFromConnection("certificates");

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} catch (error) {
							let debbugStory = {
								title: "Ошибка.",
								body: "После сохранения что-то пошло не так.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Данные почему-то не сохранились...",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
		/* _____________________________________________________*/
		/* 5. Образования                                       */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Добавление данных */
		addEducation() {
			if (
				this.checkModalInputsAll("currentEducation", [
					"name",
					"organization",
					"date",
					"speсialization",
				])
			)
				return;

			try {
				// Поиск максимального id
				let maxId = 0;
				this.specialist.connections.educations.forEach((item) => {
					if (item.id > maxId) maxId = item.id;
				});

				this.specialist.connections.educations.push({
					id: maxId + 1,
					name: this.currentEducation.data.name.body,
					organization: this.currentEducation.data.organization.body,
					date: this.currentEducation.data.date.body,
					speсialization: this.currentEducation.data.speсialization.body,
					create: true,
					delete: false,
				});
				this.closeModal("modalEducations");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При добавлении что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Обновление данных */
		updateEducation() {
			if (
				this.checkModalInputsAll("currentEducation", [
					"name",
					"organization",
					"date",
					"speсialization",
				])
			)
				return;

			try {
				let educationToUpdate = this.specialist.connections.educations.filter((education) => {
					if (education.id === this.currentEducation.data.id.body) {
						return education;
					}
				});

				for (let key in this.currentEducation.data) {
					educationToUpdate[0][key] = this.currentEducation.data[key].body;
				}
				this.closeModal("modalEducations");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При обновлении что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Сохранение */
		async saveEducationChanges() {
			let formData = new FormData();
			formData.append("type", "educations");
			formData.append("id", JSON.stringify(this.specialist.profile.data.id.body));
			formData.append("educations", JSON.stringify(this.specialist.connections.educations));

			await axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-specialist-modular`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							/* Обновление id в соответствии с изменениями */
							this.updateIdFromConnection("educations", response.data.data);

							/* Очистка удалённых элементов */
							this.clearDeletesFromConnection("educations");

							/* Обновление флагов на удаление и сохранение */
							this.clearFlagsFromConnection("educations");

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} catch (error) {
							let debbugStory = {
								title: "Ошибка.",
								body: "После сохранения что-то пошло не так.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Данные почему-то не сохранились...",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
		/* _____________________________________________________*/
		/* 6. Прошлые работы                                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Добавление */
		addWork() {
			if (
				this.checkModalInputsAll("currentWork", [
					"name",
					"organization",
					"startWork",
					"endWork",
				])
			)
				return;

			try {
				// Поиск максимального id
				let maxId = 0;
				this.specialist.connections.works.forEach((item) => {
					if (item.id > maxId) maxId = item.id;
				});

				this.specialist.connections.works.push({
					id: maxId + 1,
					name: this.currentWork.data.name.body,
					organization: this.currentWork.data.organization.body,
					startWork: this.currentWork.data.startWork.body,
					endWork: this.currentWork.data.endWork.body,
					create: true,
					delete: false,
				});

				this.closeModal("modalWorks");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При добавлении что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Обновление */
		updateWork() {
			if (
				this.checkModalInputsAll("currentWork", [
					"name",
					"organization",
					"startWork",
					"endWork",
				])
			)
				return;

			try {
				let workToUpdate = this.specialist.connections.works.filter((work) => {
					if (work.id === this.currentWork.data.id.body) {
						return work;
					}
				});

				for (let key in this.currentWork.data) {
					workToUpdate[0][key] = this.currentWork.data[key].body;
				}

				this.closeModal("modalWorks");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При обновлении что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Сохранение */
		async saveWorksChanges() {
			let formData = new FormData();
			formData.append("type", "works");
			formData.append("id", JSON.stringify(this.specialist.profile.data.id.body));
			formData.append("works", JSON.stringify(this.specialist.connections.works));

			await axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-specialist-modular`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							/* Обновление id в соответствии с изменениями */
							this.updateIdFromConnection("works", response.data.data);

							/* Очистка удалённых элементов */
							this.clearDeletesFromConnection("works");

							/* Обновление флагов на удаление и сохранение */
							this.clearFlagsFromConnection("works");

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} catch (error) {
							let debbugStory = {
								title: "Ошибка.",
								body: "После сохранения что-то пошло не так.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Данные почему-то не сохранились...",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
		/* _____________________________________________________*/
		/* ?. Общие методы                                      */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Обновление значений id */
		updateIdFromConnection(connectionName, idArray) {
			try {
				let connectionCreate = this.specialist.connections[connectionName].filter((item) => {
					if (item.create) return item;
				});

				// Изменение значений со старых id на новые из б.д.
				for (let key in connectionCreate) {
					connectionCreate[key].id = idArray.find((item) => {
						if (item.old == connectionCreate[key].id) {
							return item;
						}
					}).new;
				}
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось обновить id.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Очистка удалённых элементов */
		clearDeletesFromConnection(connectionName) {
			try {
				// Получения нового массива специалистов, помеченных на удаление
				let res = this.specialist.connections[connectionName].filter((item) => {
					if (item.delete == true) {
						return Object.assign({}, item);
					}
				});

				// Повторять, пока не будут удалены все элементы, помеченные на удаление
				while (res.length > 0) {
					/* Получение индекса элемента, помеченного на удаление из массива специалистов */
					this.specialist.connections[connectionName].splice(
						this.specialist.connections[connectionName].indexOf(res[0]),
						1
					);
					/* Обновление списка с элементами, помеченными на удаление */
					res = this.specialist.connections[connectionName].filter((item) => {
						if (item.delete == true) {
							return Object.assign({}, item);
						}
					});
				}
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось очистить удалённые элементы.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		clearFlagsFromConnection(connectionName) {
			try {
				// Сброс флагов добавления и удаления
				this.specialist.connections[connectionName].forEach((item) => {
					item.create = false;
					item.delete = false;
				});
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось сбросить флаги.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Удаление элементов со статусом на удаление */
		deleteValueFromArray(idArray) {},
		/* Фильтрация массивов */
		filterArray(column, type) {
			// Объявляем объект Intl.Collator, который обеспечивает сравнение строк с учётом языка.
			const collator = new Intl.Collator("ru");

			switch (column) {
				case "id":
					if (type == "default") {
						this.specialist.connections.certificates.sort((a, b) => {
							if (a.id > b.id) {
								return 1;
							}
							if (a.id < b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.specialist.connections.certificates.sort((a, b) => {
							if (a.id < b.id) {
								return 1;
							}
							if (a.id > b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}
					break;
				case "name":
					if (type == "default") {
						this.specialist.connections.certificates.sort((a, b) => {
							return collator.compare(a.name, b.name);
						});
					}

					if (type == "reverse") {
						this.specialist.connections.certificates.reverse((a, b) => {
							return collator.compare(a.name, b.name);
						});
					}

					break;
				default:
					break;
			}
		},
		/* Метод удаления значения из массива */
		removeArrValue(arrayName, value) {
			this.specialist.connections[arrayName] = this.specialist.connections[arrayName].filter(
				(item) => {
					if (item.id !== value.id) {
						return item;
					}
				}
			);
		},
		/* Метод пометки статуса на удаление */
		updateDeleteValue(arrayName, value) {
			let filterValue = this.specialist.connections[arrayName].filter((item) => {
				if (item.id == value) {
					return item;
				}
			});

			filterValue[0].delete = !filterValue[0].delete;
		},
		/* Метод открытия модального окна для обновления значений */
		editArrayValue(type, arrayName, value) {
			switch (arrayName) {
				case "certificates":
					/* Создание */
					if (type == "create") {
						this.openModal(type, "modalCertificates", "currentCertificate");
					}
					/* Редактирование */
					if (type == "edit") {
						let filterCertificate = this.specialist.connections.certificates.filter(
							(item) => {
								if (item.id == value.id) {
									return item;
								}
							}
						);

						for (let key in this.currentCertificate.data) {
							this.currentCertificate.data[key].body = filterCertificate[0][key];
						}

						this.openModal(type, "modalCertificates", "currentCertificate");
					}
					break;
				case "educations":
					/* Создание */
					if (type == "create") {
						this.openModal(type, "modalEducations", "currentEducation");
					}
					/* Редактирование */
					if (type == "edit") {
						let filterEducation = this.specialist.connections.educations.filter((item) => {
							if (item.id == value.id) {
								return item;
							}
						});

						for (let key in this.currentEducation.data) {
							this.currentEducation.data[key].body = filterEducation[0][key];
						}

						this.openModal(type, "modalEducations", "currentEducation");
					}
					break;
				case "works":
					/* Создание */
					if (type == "create") {
						this.openModal(type, "modalWorks", "currentWork");
					}

					/* Редактирование */
					if (type == "edit") {
						let filterWorks = this.specialist.connections.works.filter((item) => {
							if (item.id == value.id) {
								return item;
							}
						});

						for (let key in this.currentWork.data) {
							this.currentWork.data[key].body = filterWorks[0][key];
						}

						this.openModal(type, "modalWorks", "currentWork");
					}

					break;
			}
		},
	},
	mounted() {
		/* FIXME Сделать проверку на неизвестный id */
		if (this.$route.params.id !== "new" && !Number.isNaN(Number(this.$route.params.id))) {
			axios({
				method: "post",
				headers: {
					Accept: "application/json",
				},
				url: `${this.$store.state.axios.urlApi}` + `get-specialist-all`,
				data: {
					id: this.$route.params.id,
				},
			})
				.then((response) => {
					if (response.data.status) {
						// Заполнение профиля
						for (let key in response.data.data.specialist.profile) {
							this.specialist.profile.data[key].body =
								response.data.data.specialist.profile[key];
						}
						// Заполнение секций
						for (let key in response.data.data.sections) {
							this.sections[key] = response.data.data.sections[key];
						}

						for (let key in response.data.data.specialist.connections) {
							this.specialist.connections[key] =
								response.data.data.specialist.connections[key];
						}

						// Добавление поля "Прием" в клиниках
						this.sections.clinics.forEach((item) => {
							item.priem = 0;

							this.specialist.connections.clinics.forEach((itemOther) => {
								if (itemOther.id_clinic == item.id) {
									item.priem = itemOther.priem;
								}
							});
						});

						// Добавление полей delete, create
						this.specialist.connections.certificates.forEach((item) => {
							item.create = false;
							item.delete = false;
						});
						this.specialist.connections.educations.forEach((item) => {
							item.create = false;
							item.delete = false;
						});
						this.specialist.connections.works.forEach((item) => {
							item.create = false;
							item.delete = false;
						});

						// Сортировка специализаций и клиник по алфавиту
						const collator = new Intl.Collator("ru");
						this.sections.specializations.sort((a, b) => {
							return collator.compare(a.name, b.name);
						});
						this.sections.clinics.sort((a, b) => {
							return collator.compare(a.name, b.name);
						});
					} else {
						this.specialist.profile.data.id.body = "none";

						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					console.log(error);
				})
				.finally(() => {
					if (this.specialist.profile.data.id.body != "none") {
						for (let key in this.loading.loader) {
							this.loading.loader[key] = false;
						}
					}
				});
		} else if (this.$route.params.id == "new") {
			// Сброс данных всей полей профиля
			for (let key in this.specialist.profile.data) {
				switch (key) {
					case "id":
						this.specialist.profile.data[key].body = "new";
						break;
					case "path":
						this.specialist.profile.data[key].body = "storage/default/specialist.png";
						break;
					case "file":
						this.specialist.profile.data[key] = "";
						break;
					default:
						this.specialist.profile.data[key].body = "";
						break;
				}
			}

			// Сброс данных всех связей
			for (let key in this.specialist.connections) {
				this.specialist.connections[key] = [];
			}

			axios({
				method: "post",
				headers: {
					Accept: "application/json",
				},
				url: `${this.$store.state.axios.urlApi}` + `get-specialist-sections`,
			})
				.then((response) => {
					for (let key in response.data) {
						this.sections[key] = response.data[key];
					}

					// Добавление поля "Прием" в клиниках
					this.sections.clinics.forEach((item) => {
						item.priem = 0;

						this.specialist.connections.clinics.forEach((itemOther) => {
							if (itemOther.id_clinic == item.id) {
								item.priem = itemOther.priem;
							}
						});
					});
				})
				.catch((error) => {
					console.log(error);
				})
				.finally(() => {
					for (let key in this.loading.loader) {
						this.loading.loader[key] = false;
					}
				});
		} else {
			let debbugStory = {
				title: "Ошибка.",
				body: "Произошла непредвиденная ошибка.",
				type: "Error",
			};
			this.$store.commit("debuggerState", debbugStory);
		}
	},
};
</script>

<style scoped>
.children-doctor {
	display: flex;
	gap: 10px;
}

.children-doctor > :is(input, select) {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.children-doctor > :is(input:focus, select:focus) {
	border: 2px solid var(--input-border-color-active);
}

.children-doctor > input::placeholder {
	color: var(--input-placeholder-color);
}

.children-doctor > select {
	flex: 3 0 100px;
}

.children-doctor > input {
	flex: 1 0 50px;
}

:is(.specializations-list, .clinics-list) {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

:is(.specializations-list, .clinics-list) > .item {
	cursor: pointer;
	justify-content: center;
	align-items: center;

	display: grid;
	gap: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 15px;

	font-size: 18px;

	transition: all 0.2s;
}

.specializations-list > .item {
	grid-template-columns: 30px 30px 1fr;
}

.clinics-list > .item {
	grid-template-columns: 30px 30px 1fr 1fr;
}

:is(.specializations-list, .clinics-list) > .item > select {
	border: 0px;
	background-color: rgba(0, 0, 0, 0);
	font-size: 18px;
	outline: none;
}

.red {
	color: red;
}

:is(.specializations-list, .clinics-list) > .item.active {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

:is(.specializations-list, .clinics-list) > .item > input[type="checkbox"] {
	cursor: pointer;

	width: 17.5px;
	height: 17.5px;
}

:is(.specializations-list, .clinics-list) > .item > input[type="checkbox"]:checked {
	accent-color: #8fe5ee;
}

:is(.specializations-list, .clinics-list) > .item:first-of-type {
	display: grid;
	grid-template-columns: 40px 30px 1fr 1fr;
	gap: 10px;

	border: 0px;
	padding: 0px 10px;
	border-radius: 10px;

	font-size: 18px;
	color: var(--input-border-color-active);
}

:is(.specializations-list, .clinics-list) > .item:not(:first-of-type):hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

:is(.specializations-list, .clinics-list) > .item > label {
	cursor: pointer;
	font-size: 18px;
}

.container-profile {
	display: flex;
	flex-wrap: wrap;
	gap: 20px;

	animation: show-bottom-to-top-15 0.4s ease-in-out;
}

.container-profile-other {
	animation: show-bottom-to-top-15 0.4s ease-in-out;
}

.profile-image {
	flex: 1 0 500px;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 300px;
}

.profile-image > .item {
	width: 500px;
	height: 100%;
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
}

.profile-info {
	flex: 1 0 500px;
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.profile-file {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.profile-file::file-selector-button {
	flex-grow: 1;
	cursor: pointer;
	background-color: var(--button-default-color);
	border: 0px;
	border-radius: 5px;
	color: white;
	padding: 5px;
}

.profile-file:focus {
	border: 2px solid var(--input-border-color-active);
}

.profile-list {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 10px;

	animation: show-bottom-to-top-15 0.4s ease-in-out;
}

.profile-list > .item {
	display: grid;
	align-items: center;
	grid-template-columns: 1fr 1fr 30px;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;

	font-size: 18px;

	transition: all 0.2s;
}

.profile-list > .item:first-of-type {
	display: grid;
	align-items: center;
	grid-template-columns: 1fr 1fr 30px;
	gap: 10px;

	border: 0px;
	border-radius: 10px;
	padding: 0px 10px;

	color: var(--input-border-color-active);
	font-size: 18px;

	transition: all 0.2s;
}

.profile-list > .item-empty {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100%;
	color: #bcbcbc;
}

.profile-list > .item > .item-close {
	justify-self: end;
	align-self: end;
}

@media screen and (width <= 1000px) {
	.profile-info {
		flex: 1 0 0px;
	}
}

@media screen and (width <= 600px) {
	.container-profile {
		flex-direction: column;
	}

	.profile-image {
		flex: 1 0 0px;
	}

	.profile-image > .item {
		width: 100%;
	}
}
</style>

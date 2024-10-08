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
			<!-- TODO сделать область нажатия на кнопки выбора больше -->
			<!-- Список специализаций -->
			<div class="specializations-list">
				<div class="item">
					<div></div>
					<div></div>
					<div>Название</div>
				</div>
				<div
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
					<input
						type="checkbox"
						:id="`spec-${specialization.id}`"
						:value="specialization.id"
						v-model="cheked.specializations"
					/>
					<label :for="`spec-${specialization.id}`">{{ specialization.name }}</label>
				</div>
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
				<div class="item">
					<div></div>
					<div></div>
					<div>Название</div>
					<div>Статус приёма</div>
				</div>
				<div
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
					<label :for="`clin-${clinic.id}`">{{ clinic.name }}</label>
					<select v-model="clinic.priem">
						<option :value="0">Нет</option>
						<option :value="1">Да</option>
					</select>
				</div>
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
			<span class="create" v-if="modalEducations.type == 'create'"> СЕРТИФИКАТ (СОЗДАНИЕ) </span>
			<span v-if="modalEducations.type == 'edit'">СЕРТИФИКАТ (РЕДАКТИРОВАНИЕ)</span>
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
							@blur="checkModalInput('name', 'text')"
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
							:class="{ create: modal.type == 'create' }"
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
				<container-select-once :type="modalEducations.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span :class="{ create: modalEducations.type == 'create' }">СПЕЦИАЛИЗАЦИЯ*</span>
						<span
							:class="{ create: modalEducations.type == 'create' }"
							v-if="currentEducation.data.id_specialization.edited"
						>
							(ИЗМЕНЕНО)</span
						>
					</template>
					<template #select>
						<select
							v-model="currentEducation.data.id_specialization.body"
							:class="{ error: currentEducation.errors.id_specialization.status }"
						>
							<option disabled value="">Ничего не выбрано...</option>
						</select>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.id_specialization.status">
							{{ currentEducation.errors.id_specialization.body }}
						</span>
					</template>
				</container-select-once>
			</container-input>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="" v-if="modalEducations.type == 'create'"> Создать </button-claim>
				<button-default @click="" v-if="modalEducations.type == 'edit'">
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
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists/{{ $route.params.id }}</template>
	</info-bar>

	<!-- Основные данные врача -->
	<block-once>
		<block-title>
			<template #title>
				<span>ПРОФИЛЬ</span>
				<span v-if="$route.params.id == 'new'" class="create"> (СОЗДАНИЕ)</span>
			</template>
			<template #buttons>
				<icon-save-all :width="28" :height="28" @click="" v-if="$route.params.id !== 'new'" />
				<icon-save
					:width="28"
					:height="28"
					@click="saveProfileChanges"
					v-if="$route.params.id !== 'new'"
				/>
				<icon-add :width="28" :height="28" @click="$router.push('1')" v-else></icon-add>
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
						<input class="profile-file" type="file" autocomplete="off" />
					</template>
					<template #error-one>
						<span class="error" v-if="specialist.profile.errors.filename.status">
							{{ specialist.profile.errors.filename.body }}
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
						<span class="error" v-if="specialist.profile.errors.link.status">
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
						<span>ОТЧЕСТВО*</span>
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
							@blur="checkSpecialistInput('surname', 'text')"
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
							v-model="specialist.profile.data.degree.body"
							@input="specialist.profile.data.degree.edited = true"
						/>
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
							v-model="specialist.profile.data.rank.body"
							@input="specialist.profile.data.rank.edited = true"
						/>
					</template>
				</container-select-three>
				<!-- Первая работа -->
				<container-input-two
					:fieldset="true"
					:type="$route.params.id == 'new' ? 'create' : 'edit'"
				>
					<template #legend>
						<span :class="{ create: $route.params.id === 'new' }">ПЕРВАЯ РАБОТА</span>
					</template>
					<template #title-one>
						<span>НАЧАЛО ПЕРВОЙ РАБОТЫ</span>
						<span v-if="specialist.profile.data.startWorkAge.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="date"
							autocomplete="off"
							v-model="specialist.profile.data.startWorkAge.body"
							@input="specialist.profile.data.startWorkAge.edited = true"
						/>
					</template>
					<template #title-two>
						<span>ГОРОД ПЕРВОЙ РАБОТЫ</span>
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
				<container-input-two
					:fieldset="true"
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
				</container-input-two>
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
					Специализации
					<span v-show="loading.sections.specializations">
						({{ specialist.connections.specializations.length }})
					</span>
				</template>
				<template #buttons>
					<icon-save
						:width="28"
						:height="28"
						@click="saveSpecializationsChanges"
						v-if="$route.params.id !== 'new'"
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
					Клиники
					<span v-show="loading.sections.specializations">
						({{ specialist.connections.clinics.length }})
					</span>
				</template>
				<template #buttons>
					<icon-save :width="28" :height="28" @click="saveClinicsChanges" />
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
			<template #title> Сертификаты </template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveCertificateChanges" />
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
			<template #title> Образование </template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="console.log('save')" />
			</template>
		</block-title>

		<admin-specialists-table
			v-show="loading.sections.educations"
			:array="getSpecialistEducations"
			@touchRemoveArrValue="updateDeleteValue('educations', $event)"
		/>

		<!-- Загрузчик профиля -->
		<loader-child
			:isLoading="loading.loader.clinics"
			:minHeight="100"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click=""> Добавить </button-default>
		</block-buttons>
	</block-once>

	<!--____________________________________________________-->
	<!--6. Места работы                                     -->
	<!--‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾-->
	<block-once v-if="$route.params.id !== 'new'">
		<block-title>
			<template #title> Места работы </template>
			<template #buttons>
				<icon-save
					:width="28"
					:height="28"
					@click="console.log('save')"
					v-if="$route.params.id !== 'new'"
				/>
			</template>
		</block-title>

		<admin-specialists-table
			v-show="loading.sections.works"
			:array="getSpecialistWorks"
			@touchRemoveArrValue="updateDeleteValue('works', $event)"
		/>

		<!-- Загрузчик профиля -->
		<loader-child
			:isLoading="loading.loader.clinics"
			:minHeight="100"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default> Добавить </button-default>
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
					id_specialization: {
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
					id_specialization: {
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
						file: {
							body: "",
							edited: false,
						},
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
						file: "",
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
					// Для индекса
					case "category":
					case "adultDoctor":
					case "childrenDoctor":
						if (this.checkSpecialistInput(inputKeys[i], "select")) {
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
		/* Сохранение изменений */
		saveProfileChanges() {
			// Проверка на статус добавления специалиста
			if (this.specialist.profile.data.id.body === "new") return;

			try {
				if (
					this.checkSpecialistInputsAll([
						"link",
						"family",
						"name",
						"surname",
						"category",
						"adultDoctor",
						"childrenDoctor",
					])
				)
					return;

				// Сохранение данных
				axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `save-specialist-profile-changes`,
					headers: {
						Accept: "application/json",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: {
						array: {
							profile: this.specialist.profile.data,
							connections: this.specialist.connections,
						},
					},
				})
					.then((response) => {
						if (response.data.status) {
							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);

							this.clearSpecialistProfileEdited();
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
							body: "При сохранении профиля произошла ошибка.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
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
		saveSpecializationsChanges() {
			try {
				// Проверка на статус добавления специалиста
				if (this.specialist.profile.data.id.body === "new") return;

				axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `save-specialist-specializations-changes`,
					headers: {
						Accept: "application/json",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: {
						id: this.specialist.profile.data.id.body,
						array: this.specialist.connections.specializations,
					},
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
		saveClinicsChanges() {
			try {
				// Проверка на статус добавления специалиста
				if (this.specialist.profile.data.id.body === "new") return;

				axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `save-specialist-clinics-changes`,
					headers: {
						Accept: "application/json",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: {
						id: this.specialist.profile.data.id.body,
						array: this.specialist.connections.clinics,
					},
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
		saveCertificateChanges() {
			// Проверка на статус добавления специалиста
			if (this.specialist.profile.data.id.body === "new") return;

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-specialist-certificates-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					id: this.specialist.profile.data.id.body,
					array: this.specialist.connections.certificates,
				},
			})
				.then((response) => {
					if (response.data.status) {
						try {
							// Обновление id добавленных элементов на данные из бд
							for (let key in response.data.data) {
								let certificate = this.specialist.connections.certificates.filter(
									(item) => {
										if (item.id === response.data.data[key].old) {
											return item;
										}
									}
								);
								certificate[0].id = response.data.data[key].new;
							}

							// Получения нового массива специалистов, помеченных на удаление
							let res = this.specialist.connections.certificates.filter((item) => {
								if (item.delete == true) {
									return Object.assign({}, item);
								}
							});

							// Повторять, пока не будут удалены все элементы, помеченные на удаление
							while (res.length > 0) {
								/* Получение индекса элемента, помеченного на удаление из массива специалистов */
								this.specialist.connections.certificates.splice(
									this.specialist.connections.certificates.indexOf(res[0]),
									1
								);
								/* Обновление списка с элементами, помеченными на удаление */
								res = this.specialist.connections.certificates.filter((item) => {
									if (item.delete == true) {
										return Object.assign({}, item);
									}
								});
							}

							// Сброс флагов добавления и удаления
							this.specialist.connections.certificates.forEach((item) => {
								item.create = false;
								item.delete = false;
							});

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
					this.openModal(type, "modalEducations", "currentEducation");
					break;
				case "works":
					this.openModal(type, "modalWorks", "currentWork");
					break;
			}
		},
	},
	mounted() {
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
					// Заполнение профиля
					for (let key in response.data.specialist.profile) {
						this.specialist.profile.data[key].body = response.data.specialist.profile[key];
					}
					// Заполнение секций
					for (let key in response.data.sections) {
						this.sections[key] = response.data.sections[key];
					}

					for (let key in response.data.specialist.connections) {
						this.specialist.connections[key] = response.data.specialist.connections[key];
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
				})
				.catch((error) => {
					console.log(error);
				})
				.finally(() => {
					for (let key in this.loading.loader) {
						this.loading.loader[key] = false;
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
:is(.specializations-list, .clinics-list) {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

:is(.specializations-list, .clinics-list) > .item {
	display: grid;
	gap: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 15px;

	transition: all 0.2s;
}

.specializations-list > .item {
	grid-template-columns: 20px 30px 1fr;
}

.clinics-list > .item {
	grid-template-columns: 20px 30px 1fr 1fr;
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
}

:is(.specializations-list, .clinics-list) > .item > input[type="checkbox"]:checked {
	accent-color: #8fe5ee;
}

:is(.specializations-list, .clinics-list) > .item:first-of-type {
	display: grid;
	grid-template-columns: 20px 30px 1fr 1fr;
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

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.container-profile-other {
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.profile-image {
	flex: 1 0 500px;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 300px;
}

.profile-image > .item {
	width: max(100% - 100px, 500px);
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

	animation: show-bottom-to-top-15 0.5s ease-in-out;
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
</style>

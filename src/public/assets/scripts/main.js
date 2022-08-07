/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _candidatesImageGridImage__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./candidatesImageGridImage */ "./src/resources/assets/js/components/candidatesImageGridImage.vue");
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../import */ "./src/resources/assets/js/import.js");
//
//
//
//
//
//
//
//
//


/**
 * A variant of the image grid used for the selection of MAIA annotation candidates.
 *
 * @type {Object}
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_import__WEBPACK_IMPORTED_MODULE_1__.ImageGrid],
  components: {
    imageGridImage: _candidatesImageGridImage__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: {
    selectedCandidateIds: {
      type: Object,
      required: true
    },
    convertedCandidateIds: {
      type: Object,
      required: true
    }
  },
  methods: {
    isSelected: function isSelected(image) {
      return this.selectedCandidateIds.hasOwnProperty(image.id);
    },
    isConverted: function isConverted(image) {
      return this.convertedCandidateIds.hasOwnProperty(image.id);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../import */ "./src/resources/assets/js/import.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/**
 * A variant of the image grid image used for the selection of MAIA annotation candidates.
 *
 * @type {Object}
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_import__WEBPACK_IMPORTED_MODULE_0__.ImageGridImage, _import__WEBPACK_IMPORTED_MODULE_0__.AnnotationPatch],
  computed: {
    label: function label() {
      if (this.selected) {
        debugger;
        return this.image.label;
      }

      return null;
    },
    selected: function selected() {
      return this.$parent.isSelected(this.image);
    },
    converted: function converted() {
      return this.$parent.isConverted(this.image);
    },
    classObject: function classObject() {
      return {
        'image-grid__image--selected': this.selected || this.converted,
        'image-grid__image--selectable': this.selectable,
        'image-grid__image--fade': this.selectedFade,
        'image-grid__image--small-icon': this.smallIcon
      };
    },
    iconClass: function iconClass() {
      if (this.converted) {
        return 'fa-lock';
      }

      return 'fa-' + this.selectedIcon;
    },
    showIcon: function showIcon() {
      return this.selectable || this.selected || this.converted;
    },
    title: function title() {
      if (this.converted) {
        return 'This annotation candidate has been converted';
      }

      return this.selected ? 'Detach label' : 'Attach selected label';
    },
    labelStyle: function labelStyle() {
      return {
        'background-color': '#' + this.label.color
      };
    },
    id: function id() {
      return this.image.id;
    },
    uuid: function uuid() {
      return this.image.uuid;
    },
    urlTemplate: function urlTemplate() {
      // Usually this would be set in the created function but in this special
      // case this is not possible.
      return biigle.$require('maia.acUrlTemplate');
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _proposalsImageGridImage__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./proposalsImageGridImage */ "./src/resources/assets/js/components/proposalsImageGridImage.vue");
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../import */ "./src/resources/assets/js/import.js");
//
//
//
//
//
//
//
//
//


/**
 * A variant of the image grid used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_import__WEBPACK_IMPORTED_MODULE_1__.ImageGrid],
  components: {
    imageGridImage: _proposalsImageGridImage__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: {
    selectedProposalIds: {
      type: Object,
      required: true
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../import */ "./src/resources/assets/js/import.js");
//
//
//
//
//
//
//
//
//
//
//
//
//


/**
 * A variant of the image grid image used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_import__WEBPACK_IMPORTED_MODULE_0__.ImageGridImage, _import__WEBPACK_IMPORTED_MODULE_0__.AnnotationPatch],
  computed: {
    label: function label() {
      return this.image.label;
    },
    labelExists: function labelExists() {
      return this.image.label;
    },
    selected: function selected() {
      return this.$parent.selectedProposalIds.hasOwnProperty(this.image.id);
    },
    title: function title() {
      if (this.selectable) {
        return this.selected ? 'Unselect as interesting' : 'Select as interesting';
      }

      return '';
    },
    id: function id() {
      return this.image.id;
    },
    uuid: function uuid() {
      return this.image.uuid;
    },
    labelStyle: function labelStyle() {
      return {
        'background-color': '#' + this.label.color
      };
    },
    urlTemplate: function urlTemplate() {
      // Usually this would be set in the created function but in this special
      // case this is not possible.
      return biigle.$require('maia.tpUrlTemplate');
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCandidatesCanvas.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCandidatesCanvas.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var ol_Collection__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ol/Collection */ "./node_modules/ol/Collection.js");
/* harmony import */ var ol_Object__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ol/Object */ "./node_modules/ol/Object.js");
/* harmony import */ var _refineCanvas__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./refineCanvas */ "./src/resources/assets/js/components/refineCanvas.vue");
/* harmony import */ var ol_layer_Vector__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ol/layer/Vector */ "./node_modules/ol/layer/Vector.js");
/* harmony import */ var ol_source_Vector__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ol/source/Vector */ "./node_modules/ol/source/Vector.js");
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../import */ "./src/resources/assets/js/import.js");






/**
 * A variant of the annotation canvas used for the refinement of annotation candidates.
 *
 * @type {Object}
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_refineCanvas__WEBPACK_IMPORTED_MODULE_0__["default"]],
  props: {
    convertedAnnotations: {
      type: Array,
      "default": function _default() {
        return [];
      }
    }
  },
  methods: {
    createConvertedAnnotationsLayer: function createConvertedAnnotationsLayer() {
      this.convertedAnnotationFeatures = new ol_Collection__WEBPACK_IMPORTED_MODULE_2__["default"]();
      this.convertedAnnotationSource = new ol_source_Vector__WEBPACK_IMPORTED_MODULE_3__["default"]({
        features: this.convertedAnnotationFeatures
      });
      var fakeFeature = new ol_Object__WEBPACK_IMPORTED_MODULE_4__["default"]();
      fakeFeature.set('color', '999999');
      this.convertedAnnotationLayer = new ol_layer_Vector__WEBPACK_IMPORTED_MODULE_5__["default"]({
        source: this.convertedAnnotationSource,
        // Should be below unselected candidates which are at index 99. Else
        // the attach label interaction doesn't work.
        zIndex: 98,
        updateWhileAnimating: true,
        updateWhileInteracting: true,
        style: _import__WEBPACK_IMPORTED_MODULE_1__.StylesStore.features(fakeFeature)
      });
    }
  },
  watch: {
    convertedAnnotations: function convertedAnnotations(annotations) {
      this.refreshAnnotationSource(annotations, this.convertedAnnotationSource);
    }
  },
  created: function created() {
    this.createConvertedAnnotationsLayer();
    this.map.addLayer(this.convertedAnnotationLayer);
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCandidatesTab.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCandidatesTab.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../import */ "./src/resources/assets/js/import.js");

/**
 * The refine annotation candidates tab
 *
 * @type {Object}
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    labelTrees: _import__WEBPACK_IMPORTED_MODULE_0__.LabelTrees
  },
  props: {
    selectedCandidates: {
      type: Array,
      required: true
    },
    labelTrees: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      "default": false
    }
  },
  computed: {
    hasNoSelectedCandidates: function hasNoSelectedCandidates() {
      return this.selectedCandidates.length === 0;
    }
  },
  methods: {
    handleSelectedLabel: function handleSelectedLabel(label) {
      this.$emit('select', label);
    },
    handleDeselectedLabel: function handleDeselectedLabel() {
      this.$emit('select', null);
    },
    handleConvertCandidates: function handleConvertCandidates() {
      if (!this.hasNoSelectedCandidates) {
        this.$emit('convert');
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCanvas.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCanvas.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var ol_Collection__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ol/Collection */ "./node_modules/ol/Collection.js");
/* harmony import */ var ol_layer_Vector__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ol/layer/Vector */ "./node_modules/ol/layer/Vector.js");
/* harmony import */ var ol_source_Vector__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ol/source/Vector */ "./node_modules/ol/source/Vector.js");
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../import */ "./src/resources/assets/js/import.js");







/**
 * A variant of the annotation canvas used for the refinement of training proposals and
 * annotation candidates.
 *
 * @type {Object}
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_import__WEBPACK_IMPORTED_MODULE_0__.AnnotationCanvas],
  props: {
    unselectedAnnotations: {
      type: Array,
      "default": function _default() {
        return [];
      }
    }
  },
  data: function data() {
    return {
      selectingMaiaAnnotation: false
    };
  },
  computed: {
    hasAnnotations: function hasAnnotations() {
      return this.annotations.length > 0;
    }
  },
  methods: {
    handlePreviousImage: function handlePreviousImage() {
      this.$emit('previous-image');
    },
    handleNextImage: function handleNextImage() {
      this.$emit('next-image');
    },
    toggleSelectingMaiaAnnotation: function toggleSelectingMaiaAnnotation() {
      this.selectingMaiaAnnotation = !this.selectingMaiaAnnotation;
    },
    createUnselectedAnnotationsLayer: function createUnselectedAnnotationsLayer() {
      this.unselectedAnnotationFeatures = new ol_Collection__WEBPACK_IMPORTED_MODULE_1__["default"]();
      this.unselectedAnnotationSource = new ol_source_Vector__WEBPACK_IMPORTED_MODULE_2__["default"]({
        features: this.unselectedAnnotationFeatures
      });
      this.unselectedAnnotationLayer = new ol_layer_Vector__WEBPACK_IMPORTED_MODULE_3__["default"]({
        source: this.unselectedAnnotationSource,
        // Should be below regular annotations which are at index 100.
        zIndex: 99,
        updateWhileAnimating: true,
        updateWhileInteracting: true,
        style: _import__WEBPACK_IMPORTED_MODULE_0__.StylesStore.editing,
        opacity: 0.5
      });
    },
    createSelectMaiaAnnotationInteraction: function createSelectMaiaAnnotationInteraction(features) {
      this.selectMaiaAnnotationInteraction = new _import__WEBPACK_IMPORTED_MODULE_0__.AttachLabelInteraction({
        map: this.map,
        features: features
      });
      this.selectMaiaAnnotationInteraction.setActive(false);
      this.selectMaiaAnnotationInteraction.on('attach', this.handleSelectMaiaAnnotation);
    },
    handleSelectMaiaAnnotation: function handleSelectMaiaAnnotation(e) {
      this.$emit('select', e.feature.get('annotation'));
    },
    handleUnselectMaiaAnnotation: function handleUnselectMaiaAnnotation() {
      if (!this.modifyInProgress && this.selectedAnnotations.length > 0) {
        this.$emit('unselect', this.selectedAnnotations[0]);
      }
    }
  },
  watch: {
    unselectedAnnotations: function unselectedAnnotations(annotations) {
      this.refreshAnnotationSource(annotations, this.unselectedAnnotationSource);
    },
    selectingMaiaAnnotation: function selectingMaiaAnnotation(selecting) {
      this.selectMaiaAnnotationInteraction.setActive(selecting);
    }
  },
  created: function created() {
    this.createUnselectedAnnotationsLayer();
    this.map.addLayer(this.unselectedAnnotationLayer); // Disallow unselecting of currently highlighted training proposal.

    this.selectInteraction.setActive(false);

    if (this.canModify) {
      this.createSelectMaiaAnnotationInteraction(this.unselectedAnnotationFeatures);
      this.map.addInteraction(this.selectMaiaAnnotationInteraction);
      _import__WEBPACK_IMPORTED_MODULE_0__.Keyboard.on('Delete', this.handleUnselectMaiaAnnotation, 0, this.listenerSet);
    } // Disable shortcut for the measure interaction.


    _import__WEBPACK_IMPORTED_MODULE_0__.Keyboard.off('Shift+f', this.toggleMeasuring, this.listenerSet);
  },
  mounted: function mounted() {
    // Disable shortcut for the translate interaction.
    _import__WEBPACK_IMPORTED_MODULE_0__.Keyboard.off('m', this.toggleTranslating, this.listenerSet);
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineProposalsTab.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineProposalsTab.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * The refine training proposals tab
 *
 * @type {Object}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    selectedProposals: {
      type: Array,
      required: true
    },
    seenProposals: {
      type: Array,
      required: true
    }
  },
  computed: {
    numberSelectedProposals: function numberSelectedProposals() {
      return this.selectedProposals.length;
    },
    numberSeenProposals: function numberSeenProposals() {
      return this.seenProposals.length;
    },
    hasNoSelectedProposals: function hasNoSelectedProposals() {
      return this.numberSelectedProposals === 0;
    },
    hasSeenAllSelectedProposals: function hasSeenAllSelectedProposals() {
      return this.numberSelectedProposals > 0 && this.numberSelectedProposals === this.numberSeenProposals;
    },
    textClass: function textClass() {
      return this.hasSeenAllSelectedProposals ? 'text-success' : '';
    },
    buttonClass: function buttonClass() {
      return this.hasSeenAllSelectedProposals ? 'btn-success' : 'btn-default';
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/selectCandidatesTab.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/selectCandidatesTab.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../import */ "./src/resources/assets/js/import.js");

/**
 * The select annotation candidates tab
 *
 * @type {Object}
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    labelTrees: _import__WEBPACK_IMPORTED_MODULE_0__.LabelTrees
  },
  props: {
    labelTrees: {
      type: Array,
      required: true
    }
  },
  methods: {
    handleSelectedLabel: function handleSelectedLabel(label) {
      this.$emit('select', label);
    },
    handleDeselectedLabel: function handleDeselectedLabel() {
      this.$emit('select', null);
    },
    proceed: function proceed() {
      this.$emit('proceed');
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/selectProposalsTab.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/selectProposalsTab.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * The select training proposals tab
 *
 * @type {Object}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    proposals: {
      type: Array,
      required: true
    },
    selectedProposals: {
      type: Array,
      required: true
    }
  },
  computed: {
    selectedProposalsCount: function selectedProposalsCount() {
      return this.selectedProposals.length;
    },
    proposalsCount: function proposalsCount() {
      return this.proposals.length;
    }
  },
  methods: {
    proceed: function proceed() {
      this.$emit('proceed');
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/form.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/form.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _api_areaKnowledgeTransferVolume__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./api/areaKnowledgeTransferVolume */ "./src/resources/assets/js/api/areaKnowledgeTransferVolume.js");
/* harmony import */ var _api_knowledgeTransferVolume__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./api/knowledgeTransferVolume */ "./src/resources/assets/js/api/knowledgeTransferVolume.js");
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./import */ "./src/resources/assets/js/import.js");





/**
 * View model for the form to submit new MAIA jobs
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_import__WEBPACK_IMPORTED_MODULE_2__.LoaderMixin],
  components: {
    typeahead: _import__WEBPACK_IMPORTED_MODULE_2__.LabelTypeahead
  },
  data: function data() {
    return {
      volumeId: null,
      showAdvanced: false,
      shouldFetchLabels: false,
      labels: [],
      selectedLabels: [],
      submitted: false,
      trainScheme: [],
      trainingDataMethod: '',
      distanceKnowledgeTransferVolumes: [],
      areaKnowledgeTransferVolumes: [],
      knowledgeTransferVolume: null,
      shouldFetchAreaKnowledgeTransferVolumes: false,
      shouldFetchDistanceKnowledgeTransferVolumes: false,
      knowledgeTransferLabelCache: [],
      selectedKnowledgeTransferLabels: []
    };
  },
  computed: {
    hasLabels: function hasLabels() {
      return this.labels.length > 0;
    },
    hasSelectedLabels: function hasSelectedLabels() {
      return this.selectedLabels.length > 0;
    },
    useExistingAnnotations: function useExistingAnnotations() {
      return this.trainingDataMethod === 'own_annotations';
    },
    useNoveltyDetection: function useNoveltyDetection() {
      return this.trainingDataMethod === 'novelty_detection';
    },
    useKnowledgeTransfer: function useKnowledgeTransfer() {
      return this.trainingDataMethod === 'knowledge_transfer' || this.trainingDataMethod === 'area_knowledge_transfer';
    },
    canSubmit: function canSubmit() {
      return this.submitted || this.useKnowledgeTransfer && !this.knowledgeTransferVolume;
    },
    knowledgeTransferVolumes: function knowledgeTransferVolumes() {
      if (this.trainingDataMethod === 'area_knowledge_transfer') {
        return this.areaKnowledgeTransferVolumes;
      }

      return this.distanceKnowledgeTransferVolumes;
    },
    shouldFetchKnowledgeTransferVolumes: function shouldFetchKnowledgeTransferVolumes() {
      return this.shouldFetchDistanceKnowledgeTransferVolumes || this.shouldFetchAreaKnowledgeTransferVolumes;
    },
    hasNoKnowledgeTransferVolumes: function hasNoKnowledgeTransferVolumes() {
      return this.shouldFetchKnowledgeTransferVolumes && !this.loading && this.knowledgeTransferVolumes.length === 0;
    },
    hasSelectedKnowledgeTransferLabels: function hasSelectedKnowledgeTransferLabels() {
      return this.selectedKnowledgeTransferLabels.length > 0;
    },
    knowledgeTransferLabels: function knowledgeTransferLabels() {
      if (!this.knowledgeTransferVolume) {
        return [];
      }

      var volumeId = this.knowledgeTransferVolume.id;

      if (!this.knowledgeTransferLabelCache.hasOwnProperty(volumeId)) {
        this.fetchKnowledgeTransferLabels(volumeId);
      }

      return this.knowledgeTransferLabelCache[volumeId];
    }
  },
  methods: {
    toggle: function toggle() {
      this.showAdvanced = !this.showAdvanced;
    },
    setLabels: function setLabels(response) {
      this.labels = response.body;
    },
    handleSelectedLabel: function handleSelectedLabel(label) {
      if (this.selectedLabels.indexOf(label) === -1) {
        this.selectedLabels.push(label);
      }
    },
    handleUnselectLabel: function handleUnselectLabel(label) {
      var index = this.selectedLabels.indexOf(label);

      if (index >= 0) {
        this.selectedLabels.splice(index, 1);
      }
    },
    submit: function submit() {
      this.submitted = true;
    },
    removeTrainStep: function removeTrainStep(index) {
      this.trainScheme.splice(index, 1);
    },
    addTrainStep: function addTrainStep() {
      var step = {
        layers: 'heads',
        epochs: 10,
        learning_rate: 0.001
      };

      if (this.trainScheme.length > 0) {
        var last = this.trainScheme[this.trainScheme.length - 1];
        step.layers = last.layers;
        step.epochs = last.epochs;
        step.learning_rate = last.learning_rate;
      }

      this.trainScheme.push(step);
    },
    handleSelectedKnowledgeTransferVolume: function handleSelectedKnowledgeTransferVolume(volume) {
      this.knowledgeTransferVolume = volume;
      this.selectedKnowledgeTransferLabels = [];
    },
    parseKnowledgeTransferVolumes: function parseKnowledgeTransferVolumes(response) {
      var _this = this;

      return response.body.filter(function (volume) {
        return volume.id !== _this.volumeId;
      }).map(function (volume) {
        volume.description = volume.projects.map(function (project) {
          return project.name;
        }).join(', ');
        return volume;
      });
    },
    fetchLabels: function fetchLabels(id) {
      this.startLoading();
      var promise = this.$http.get('api/v1/volumes{/id}/annotation-labels', {
        params: {
          id: id
        }
      });
      promise["finally"](this.finishLoading);
      return promise;
    },
    fetchKnowledgeTransferLabels: function fetchKnowledgeTransferLabels(id) {
      var _this2 = this;

      this.fetchLabels(id).then(function (response) {
        _this2.knowledgeTransferLabelCache[id] = response.body;
      }, _import__WEBPACK_IMPORTED_MODULE_2__.handleErrorResponse);
    },
    handleSelectedKnowledgeTransferLabel: function handleSelectedKnowledgeTransferLabel(label) {
      if (this.selectedKnowledgeTransferLabels.indexOf(label) === -1) {
        this.selectedKnowledgeTransferLabels.push(label);
      }
    },
    handleUnselectKnowledgeTransferLabel: function handleUnselectKnowledgeTransferLabel(label) {
      var index = this.selectedKnowledgeTransferLabels.indexOf(label);

      if (index >= 0) {
        this.selectedKnowledgeTransferLabels.splice(index, 1);
      }
    }
  },
  watch: {
    useExistingAnnotations: function useExistingAnnotations(use) {
      if (use) {
        this.shouldFetchLabels = true;
      }
    },
    shouldFetchLabels: function shouldFetchLabels(fetch) {
      if (fetch) {
        this.fetchLabels(this.volumeId).then(this.setLabels, _import__WEBPACK_IMPORTED_MODULE_2__.handleErrorResponse);
      }
    },
    trainingDataMethod: function trainingDataMethod(method, oldMethod) {
      if (method === 'knowledge_transfer') {
        this.shouldFetchDistanceKnowledgeTransferVolumes = true;
      } else if (method === 'area_knowledge_transfer') {
        this.shouldFetchAreaKnowledgeTransferVolumes = true;
      }

      if (oldMethod === 'knowledge_transfer' || oldMethod === 'area_knowledge_transfer') {
        this.knowledgeTransferVolume = null;
        this.selectedKnowledgeTransferLabels = [];
        this.$refs.ktTypeahead.clear();
      }
    },
    shouldFetchDistanceKnowledgeTransferVolumes: function shouldFetchDistanceKnowledgeTransferVolumes(fetch) {
      var _this3 = this;

      if (fetch) {
        this.startLoading();
        _api_knowledgeTransferVolume__WEBPACK_IMPORTED_MODULE_1__["default"].get().then(this.parseKnowledgeTransferVolumes, _import__WEBPACK_IMPORTED_MODULE_2__.handleErrorResponse).then(function (volumes) {
          return _this3.distanceKnowledgeTransferVolumes = volumes;
        })["finally"](this.finishLoading);
      }
    },
    shouldFetchAreaKnowledgeTransferVolumes: function shouldFetchAreaKnowledgeTransferVolumes(fetch) {
      var _this4 = this;

      if (fetch) {
        this.startLoading();
        _api_areaKnowledgeTransferVolume__WEBPACK_IMPORTED_MODULE_0__["default"].get().then(this.parseKnowledgeTransferVolumes, _import__WEBPACK_IMPORTED_MODULE_2__.handleErrorResponse).then(function (volumes) {
          return _this4.areaKnowledgeTransferVolumes = volumes;
        })["finally"](this.finishLoading);
      }
    }
  },
  created: function created() {
    this.volumeId = biigle.$require('maia.volumeId');
    this.trainScheme = biigle.$require('maia.trainScheme');
    this.showAdvanced = biigle.$require('maia.hasErrors');
    this.trainingDataMethod = biigle.$require('maia.trainingDataMethod');

    if (this.useExistingAnnotations) {
      this.shouldFetchLabels = true;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/show.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/show.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _api_annotationCandidate__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./api/annotationCandidate */ "./src/resources/assets/js/api/annotationCandidate.js");
/* harmony import */ var _components_candidatesImageGrid__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/candidatesImageGrid */ "./src/resources/assets/js/components/candidatesImageGrid.vue");
/* harmony import */ var _api_maiaJob__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./api/maiaJob */ "./src/resources/assets/js/api/maiaJob.js");
/* harmony import */ var _api_trainingProposal__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./api/trainingProposal */ "./src/resources/assets/js/api/trainingProposal.js");
/* harmony import */ var _components_proposalsImageGrid__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/proposalsImageGrid */ "./src/resources/assets/js/components/proposalsImageGrid.vue");
/* harmony import */ var _components_refineCandidatesCanvas__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/refineCandidatesCanvas */ "./src/resources/assets/js/components/refineCandidatesCanvas.vue");
/* harmony import */ var _components_refineCandidatesTab__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/refineCandidatesTab */ "./src/resources/assets/js/components/refineCandidatesTab.vue");
/* harmony import */ var _components_refineCanvas__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/refineCanvas */ "./src/resources/assets/js/components/refineCanvas.vue");
/* harmony import */ var _components_refineProposalsTab__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/refineProposalsTab */ "./src/resources/assets/js/components/refineProposalsTab.vue");
/* harmony import */ var _components_selectCandidatesTab__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./components/selectCandidatesTab */ "./src/resources/assets/js/components/selectCandidatesTab.vue");
/* harmony import */ var _components_selectProposalsTab__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./components/selectProposalsTab */ "./src/resources/assets/js/components/selectProposalsTab.vue");
/* harmony import */ var _import__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./import */ "./src/resources/assets/js/import.js");

















 // Proposals = Training Proposals
// Candidates = Annotation Candidates
// We have to take very great care from preventing Vue to make the training proposals
// and annotation candidates fully reactive. These can be huge arrays and Vue is not
// fast enough to ensure a fluid UX if they are fully reactive.

var PROPOSALS = [];
var PROPOSALS_BY_ID = {};
var CANDIDATES = [];
var CANDIDATES_BY_ID = {};
/**
 * View model for the main view of a MAIA job
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_import__WEBPACK_IMPORTED_MODULE_11__.LoaderMixin],
  components: {
    sidebar: _import__WEBPACK_IMPORTED_MODULE_11__.Sidebar,
    sidebarTab: _import__WEBPACK_IMPORTED_MODULE_11__.SidebarTab,
    selectProposalsTab: _components_selectProposalsTab__WEBPACK_IMPORTED_MODULE_10__["default"],
    proposalsImageGrid: _components_proposalsImageGrid__WEBPACK_IMPORTED_MODULE_4__["default"],
    refineProposalsTab: _components_refineProposalsTab__WEBPACK_IMPORTED_MODULE_8__["default"],
    refineCanvas: _components_refineCanvas__WEBPACK_IMPORTED_MODULE_7__["default"],
    refineCandidatesCanvas: _components_refineCandidatesCanvas__WEBPACK_IMPORTED_MODULE_5__["default"],
    selectCandidatesTab: _components_selectCandidatesTab__WEBPACK_IMPORTED_MODULE_9__["default"],
    candidatesImageGrid: _components_candidatesImageGrid__WEBPACK_IMPORTED_MODULE_1__["default"],
    refineCandidatesTab: _components_refineCandidatesTab__WEBPACK_IMPORTED_MODULE_6__["default"]
  },
  data: function data() {
    return {
      job: null,
      states: null,
      labelTrees: [],
      visitedSelectProposalsTab: false,
      visitedRefineProposalsTab: false,
      visitedSelectCandidatesTab: false,
      visitedRefineCandidatesTab: false,
      openTab: 'info',
      fetchProposalsPromise: null,
      hasProposals: false,
      // Track these manually and not via a computed property because the number of
      // training proposals can be huge.
      selectedProposalIds: {},
      seenProposalIds: {},
      lastSelectedProposal: null,
      currentProposalImage: null,
      currentProposalImageIndex: null,
      currentProposals: [],
      currentProposalsById: {},
      focussedProposal: null,
      proposalAnnotationCache: {},
      fetchCandidatesPromise: null,
      hasCandidates: false,
      selectedCandidateIds: {},
      convertedCandidateIds: {},
      lastSelectedCandidate: null,
      currentCandidateImage: null,
      currentCandidateImageIndex: null,
      currentCandidates: [],
      currentCandidatesById: {},
      focussedCandidate: null,
      candidateAnnotationCache: {},
      selectedLabel: null,
      // Increasing counter to use for sorting of selected proposals and
      // candidates. If a proposal/candidate is selected it should always be sorted
      // after all previously selected ones, regardless of it's ID or position
      // in the data returned from the API.
      sequenceCounter: 0
    };
  },
  computed: {
    infoTabOpen: function infoTabOpen() {
      return this.openTab === 'info';
    },
    selectProposalsTabOpen: function selectProposalsTabOpen() {
      return this.openTab === 'select-proposals';
    },
    refineProposalsTabOpen: function refineProposalsTabOpen() {
      return this.openTab === 'refine-proposals';
    },
    selectCandidatesTabOpen: function selectCandidatesTabOpen() {
      return this.openTab === 'select-candidates';
    },
    refineCandidatesTabOpen: function refineCandidatesTabOpen() {
      return this.openTab === 'refine-candidates';
    },
    isInTrainingProposalState: function isInTrainingProposalState() {
      return this.job.state_id === this.states['training-proposals'];
    },
    isInAnnotationCandidateState: function isInAnnotationCandidateState() {
      return this.job.state_id === this.states['annotation-candidates'];
    },
    proposals: function proposals() {
      if (this.hasProposals) {
        return PROPOSALS;
      }

      return [];
    },
    selectedProposals: function selectedProposals() {
      var selectedIds = this.selectedProposalIds;
      return Object.keys(selectedIds).map(function (id) {
        return PROPOSALS_BY_ID[id];
      }) // Sort by image ID first and the proposals of the same image by
      // their assigned sequence ID. This way proposals that were selected
      // during refinement are always focussed next.
      .sort(function (a, b) {
        if (a.image_id === b.image_id) {
          return selectedIds[a.id] - selectedIds[b.id];
        }

        return a.image_id - b.image_id;
      });
    },
    // The thumbnails of unselected proposals are deleted when the job advances
    // to instance segmentation so we only want to show the selected proposals
    // when this happened.
    proposalsForSelectView: function proposalsForSelectView() {
      if (this.isInTrainingProposalState) {
        return this.proposals;
      } else {
        return this.selectedProposals;
      }
    },
    hasSelectedProposals: function hasSelectedProposals() {
      return this.selectedProposals.length > 0;
    },
    proposalImageIds: function proposalImageIds() {
      var tmp = {};
      this.proposals.forEach(function (p) {
        return tmp[p.image_id] = undefined;
      });
      return Object.keys(tmp).map(function (id) {
        return parseInt(id, 10);
      });
    },
    currentProposalImageId: function currentProposalImageId() {
      return this.proposalImageIds[this.currentProposalImageIndex];
    },
    nextProposalImageIndex: function nextProposalImageIndex() {
      return (this.currentProposalImageIndex + 1) % this.proposalImageIds.length;
    },
    nextProposalImageId: function nextProposalImageId() {
      return this.proposalImageIds[this.nextProposalImageIndex];
    },
    nextFocussedProposalImageId: function nextFocussedProposalImageId() {
      if (this.nextFocussedProposal) {
        return this.nextFocussedProposal.image_id;
      }

      return this.nextProposalImageId;
    },
    previousProposalImageIndex: function previousProposalImageIndex() {
      return (this.currentProposalImageIndex - 1 + this.proposalImageIds.length) % this.proposalImageIds.length;
    },
    previousProposalImageId: function previousProposalImageId() {
      return this.proposalImageIds[this.previousProposalImageIndex];
    },
    hasCurrentProposalImage: function hasCurrentProposalImage() {
      return this.currentProposalImage !== null;
    },
    currentSelectedProposals: function currentSelectedProposals() {
      var _this = this;

      return this.currentProposals.filter(function (p) {
        return _this.selectedProposalIds.hasOwnProperty(p.id);
      });
    },
    currentUnselectedProposals: function currentUnselectedProposals() {
      var _this2 = this;

      return this.currentProposals.filter(function (p) {
        return !_this2.selectedProposalIds.hasOwnProperty(p.id);
      });
    },
    previousFocussedProposal: function previousFocussedProposal() {
      var index = (this.selectedProposals.indexOf(this.focussedProposal) - 1 + this.selectedProposals.length) % this.selectedProposals.length;
      return this.selectedProposals[index];
    },
    nextFocussedProposal: function nextFocussedProposal() {
      var index = (this.selectedProposals.indexOf(this.focussedProposal) + 1) % this.selectedProposals.length;
      return this.selectedProposals[index];
    },
    // The focussed training proposal might change while the refine tab tool is
    // closed but it should be updated only when it it opened again. Else the
    // canvas would not update correctly.
    focussedProposalToShow: function focussedProposalToShow() {
      if (this.refineProposalsTabOpen) {
        return this.focussedProposal;
      }

      return null;
    },
    focussedProposalArray: function focussedProposalArray() {
      var _this3 = this;

      if (this.focussedProposalToShow) {
        return this.currentSelectedProposals.filter(function (p) {
          return p.id === _this3.focussedProposalToShow.id;
        });
      }

      return [];
    },
    selectedAndSeenProposals: function selectedAndSeenProposals() {
      var _this4 = this;

      return this.selectedProposals.filter(function (p) {
        return _this4.seenProposalIds.hasOwnProperty(p.id);
      });
    },
    candidates: function candidates() {
      if (this.hasCandidates) {
        return CANDIDATES;
      }

      return [];
    },
    selectedCandidates: function selectedCandidates() {
      var selectedIds = this.selectedCandidateIds;
      return Object.keys(selectedIds).map(function (id) {
        return CANDIDATES_BY_ID[id];
      }) // Sort by image ID first and the candidates of the same image by
      // their assigned sequence ID. This way candidates that were selected
      // during refinement are always focussed next.
      .sort(function (a, b) {
        if (a.image_id === b.image_id) {
          return selectedIds[a.id] - selectedIds[b.id];
        }

        return a.image_id - b.image_id;
      });
    },
    hasSelectedCandidates: function hasSelectedCandidates() {
      return this.selectedCandidates.length > 0;
    },
    candidateImageIds: function candidateImageIds() {
      var tmp = {};
      this.candidates.forEach(function (p) {
        return tmp[p.image_id] = undefined;
      });
      return Object.keys(tmp).map(function (id) {
        return parseInt(id, 10);
      });
    },
    currentCandidateImageId: function currentCandidateImageId() {
      return this.candidateImageIds[this.currentCandidateImageIndex];
    },
    nextCandidateImageIndex: function nextCandidateImageIndex() {
      return (this.currentCandidateImageIndex + 1) % this.candidateImageIds.length;
    },
    nextCandidateImageId: function nextCandidateImageId() {
      return this.candidateImageIds[this.nextCandidateImageIndex];
    },
    previousCandidateImageIndex: function previousCandidateImageIndex() {
      return (this.currentCandidateImageIndex - 1 + this.candidateImageIds.length) % this.candidateImageIds.length;
    },
    previousCandidateImageId: function previousCandidateImageId() {
      return this.candidateImageIds[this.previousCandidateImageIndex];
    },
    hasCurrentCandidateImage: function hasCurrentCandidateImage() {
      return this.currentCandidateImage !== null;
    },
    currentSelectedCandidates: function currentSelectedCandidates() {
      var _this5 = this;

      return this.currentCandidates.filter(function (p) {
        return _this5.selectedCandidateIds.hasOwnProperty(p.id);
      });
    },
    currentUnselectedCandidates: function currentUnselectedCandidates() {
      var _this6 = this;

      return this.currentCandidates.filter(function (p) {
        return !_this6.selectedCandidateIds.hasOwnProperty(p.id) && !_this6.convertedCandidateIds.hasOwnProperty(p.id);
      });
    },
    currentConvertedCandidates: function currentConvertedCandidates() {
      var _this7 = this;

      return this.currentCandidates.filter(function (p) {
        return _this7.convertedCandidateIds.hasOwnProperty(p.id);
      });
    },
    previousFocussedCandidate: function previousFocussedCandidate() {
      var index = (this.selectedCandidates.indexOf(this.focussedCandidate) - 1 + this.selectedCandidates.length) % this.selectedCandidates.length;
      return this.selectedCandidates[index];
    },
    nextFocussedCandidate: function nextFocussedCandidate() {
      var index = (this.selectedCandidates.indexOf(this.focussedCandidate) + 1) % this.selectedCandidates.length;
      return this.selectedCandidates[index];
    },
    nextFocussedCandidateImageId: function nextFocussedCandidateImageId() {
      if (this.nextFocussedCandidate) {
        return this.nextFocussedCandidate.image_id;
      }

      return this.nextCandidateImageId;
    },
    // The focussed training proposal might change while the refine tab tool is
    // closed but it should be updated only when it it opened again. Else the
    // canvas would not update correctly.
    focussedCandidateToShow: function focussedCandidateToShow() {
      if (this.refineCandidatesTabOpen) {
        return this.focussedCandidate;
      }

      return null;
    },
    focussedCandidateArray: function focussedCandidateArray() {
      var _this8 = this;

      if (this.focussedCandidateToShow) {
        return this.currentSelectedCandidates.filter(function (p) {
          return p.id === _this8.focussedCandidateToShow.id;
        });
      }

      return [];
    }
  },
  methods: {
    handleSidebarToggle: function handleSidebarToggle() {
      var _this9 = this;

      this.$nextTick(function () {
        if (_this9.$refs.proposalsImageGrid) {
          _this9.$refs.proposalsImageGrid.$emit('resize');
        }

        if (_this9.$refs.candidatesImageGrid) {
          _this9.$refs.candidatesImageGrid.$emit('resize');
        }
      });
    },
    handleTabOpened: function handleTabOpened(tab) {
      this.openTab = tab;
    },
    setProposals: function setProposals(response) {
      var _this10 = this;

      PROPOSALS = response.body;
      PROPOSALS.forEach(function (p) {
        PROPOSALS_BY_ID[p.id] = p;

        _this10.setSelectedProposalId(p);
      });
      this.hasProposals = PROPOSALS.length > 0;
    },
    fetchProposals: function fetchProposals() {
      if (!this.fetchProposalsPromise) {
        this.startLoading();
        this.fetchProposalsPromise = _api_maiaJob__WEBPACK_IMPORTED_MODULE_2__["default"].getTrainingProposals({
          id: this.job.id
        });
        this.fetchProposalsPromise.then(this.setProposals, _import__WEBPACK_IMPORTED_MODULE_11__.handleErrorResponse)["finally"](this.finishLoading);
      }

      return this.fetchProposalsPromise;
    },
    openRefineProposalsTab: function openRefineProposalsTab() {
      this.openTab = 'refine-proposals';
    },
    openRefineCandidatesTab: function openRefineCandidatesTab() {
      this.openTab = 'refine-candidates';
    },
    updateSelectProposal: function updateSelectProposal(proposal, selected) {
      var _this11 = this;

      proposal.selected = selected;
      this.setSelectedProposalId(proposal);
      var promise = _api_trainingProposal__WEBPACK_IMPORTED_MODULE_3__["default"].update({
        id: proposal.id
      }, {
        selected: selected
      });
      promise["catch"](function (response) {
        (0,_import__WEBPACK_IMPORTED_MODULE_11__.handleErrorResponse)(response);
        proposal.selected = !selected;

        _this11.setSelectedProposalId(proposal);
      });
      return promise;
    },
    setSelectedProposalId: function setSelectedProposalId(proposal) {
      if (proposal.selected) {
        Vue.set(this.selectedProposalIds, proposal.id, this.getSequenceId());
      } else {
        Vue["delete"](this.selectedProposalIds, proposal.id);
      }
    },
    setSeenProposalId: function setSeenProposalId(p) {
      Vue.set(this.seenProposalIds, p.id, true);
    },
    fetchProposalAnnotations: function fetchProposalAnnotations(id) {
      if (!this.proposalAnnotationCache.hasOwnProperty(id)) {
        this.proposalAnnotationCache[id] = _api_maiaJob__WEBPACK_IMPORTED_MODULE_2__["default"].getTrainingProposalPoints({
          jobId: this.job.id,
          imageId: id
        }).then(this.parseAnnotations);
      }

      return this.proposalAnnotationCache[id];
    },
    parseAnnotations: function parseAnnotations(response) {
      return Object.keys(response.body).map(function (id) {
        return {
          id: parseInt(id, 10),
          shape: 'Circle',
          points: response.body[id]
        };
      });
    },
    setCurrentProposalImageAndAnnotations: function setCurrentProposalImageAndAnnotations(args) {
      var _this12 = this;

      this.currentProposalImage = args[0];
      this.currentProposals = args[1];
      this.currentProposalsById = {};
      this.currentProposals.forEach(function (p) {
        _this12.currentProposalsById[p.id] = p;
      });
    },
    cacheNextProposalImage: function cacheNextProposalImage() {
      // Do nothing if there is only one image.
      if (this.currentProposalImageId !== this.nextFocussedProposalImageId) {
        _import__WEBPACK_IMPORTED_MODULE_11__.ImagesStore.fetchImage(this.nextFocussedProposalImageId) // Ignore errors in this case. The application will try to reload
        // the data again if the user switches to the respective image
        // and display the error message then.
        ["catch"](function () {});
      }
    },
    cacheNextProposalAnnotations: function cacheNextProposalAnnotations() {
      // Do nothing if there is only one image.
      if (this.currentProposalImageId !== this.nextFocussedProposalImageId) {
        this.fetchProposalAnnotations(this.nextFocussedProposalImageId) // Ignore errors in this case. The application will try to reload
        // the data again if the user switches to the respective image
        // and display the error message then.
        ["catch"](function () {});
      }
    },
    handlePreviousProposalImage: function handlePreviousProposalImage() {
      this.currentProposalImageIndex = this.previousProposalImageIndex;
    },
    handlePreviousProposal: function handlePreviousProposal() {
      if (this.previousFocussedProposal) {
        this.focussedProposal = this.previousFocussedProposal;
      } else {
        this.handlePreviousProposalImage();
      }
    },
    handleNextProposalImage: function handleNextProposalImage() {
      this.currentProposalImageIndex = this.nextProposalImageIndex;
    },
    handleNextProposal: function handleNextProposal() {
      if (this.nextFocussedProposal) {
        this.focussedProposal = this.nextFocussedProposal;
      } else {
        this.handleNextProposalImage();
      }
    },
    handleRefineProposal: function handleRefineProposal(proposals) {
      Vue.Promise.all(proposals.map(this.updateProposalPoints))["catch"](_import__WEBPACK_IMPORTED_MODULE_11__.handleErrorResponse);
    },
    updateProposalPoints: function updateProposalPoints(proposal) {
      var toUpdate = this.currentProposalsById[proposal.id];
      return _api_trainingProposal__WEBPACK_IMPORTED_MODULE_3__["default"].update({
        id: proposal.id
      }, {
        points: proposal.points
      }).then(function () {
        toUpdate.points = proposal.points;
      });
    },
    focusProposalToShow: function focusProposalToShow() {
      if (this.focussedProposalToShow) {
        var p = this.currentProposalsById[this.focussedProposalToShow.id];

        if (p) {
          this.$refs.refineProposalsCanvas.focusAnnotation(p, true, false);
        }
      }
    },
    handleSelectedProposal: function handleSelectedProposal(proposal, event) {
      if (proposal.selected) {
        this.unselectProposal(proposal);
      } else {
        if (event.shiftKey && this.lastSelectedProposal) {
          this.doForEachBetween(this.proposals, proposal, this.lastSelectedProposal, this.selectProposal);
        } else {
          this.lastSelectedProposal = proposal;
          this.selectProposal(proposal);
        }
      }
    },
    selectProposal: function selectProposal(proposal) {
      this.updateSelectProposal(proposal, true).then(this.maybeInitFocussedProposal);
    },
    unselectProposal: function unselectProposal(proposal) {
      var next = this.nextFocussedProposal;
      this.updateSelectProposal(proposal, false).bind(this).then(function () {
        this.maybeUnsetFocussedProposal(proposal, next);
      });
    },
    maybeInitFocussedProposal: function maybeInitFocussedProposal() {
      if (!this.focussedProposal && this.hasSelectedProposals) {
        this.focussedProposal = this.selectedProposals[0];
      }
    },
    maybeUnsetFocussedProposal: function maybeUnsetFocussedProposal(proposal, next) {
      if (this.focussedProposal && this.focussedProposal.id === proposal.id) {
        if (next && next.id !== proposal.id) {
          this.focussedProposal = next;
        } else {
          this.focussedProposal = null;
        }
      }
    },
    maybeInitCurrentProposalImage: function maybeInitCurrentProposalImage() {
      if (this.currentProposalImageIndex === null) {
        this.currentProposalImageIndex = 0;
      }
    },
    maybeInitCurrentCandidateImage: function maybeInitCurrentCandidateImage() {
      if (this.currentCandidateImageIndex === null) {
        this.currentCandidateImageIndex = 0;
      }
    },
    handleLoadingError: function handleLoadingError(message) {
      _import__WEBPACK_IMPORTED_MODULE_11__.Messages.danger(message);
    },
    setSelectedCandidateId: function setSelectedCandidateId(candidate) {
      if (candidate.label && !candidate.annotation_id) {
        Vue.set(this.selectedCandidateIds, candidate.id, this.getSequenceId());
      } else {
        Vue["delete"](this.selectedCandidateIds, candidate.id);
      }
    },
    setConvertedCandidateId: function setConvertedCandidateId(candidate) {
      if (candidate.annotation_id) {
        Vue.set(this.convertedCandidateIds, candidate.id, candidate.annotation_id);
      } else {
        Vue["delete"](this.convertedCandidateIds, candidate.id);
      }
    },
    setCandidates: function setCandidates(response) {
      var _this13 = this;

      CANDIDATES = response.body;
      CANDIDATES.forEach(function (p) {
        CANDIDATES_BY_ID[p.id] = p;

        _this13.setSelectedCandidateId(p);

        _this13.setConvertedCandidateId(p);
      });
      this.hasCandidates = CANDIDATES.length > 0;
    },
    fetchCandidates: function fetchCandidates(force) {
      if (!this.fetchCandidatesPromise || force) {
        this.startLoading();
        this.fetchCandidatesPromise = _api_maiaJob__WEBPACK_IMPORTED_MODULE_2__["default"].getAnnotationCandidates({
          id: this.job.id
        });
        this.fetchCandidatesPromise.then(this.setCandidates, _import__WEBPACK_IMPORTED_MODULE_11__.handleErrorResponse)["finally"](this.finishLoading);
      }

      return this.fetchCandidatesPromise;
    },
    handleSelectedCandidate: function handleSelectedCandidate(candidate, event) {
      if (candidate.label) {
        this.unselectCandidate(candidate);
      } else {
        if (event.shiftKey && this.lastSelectedCandidate && this.selectedLabel) {
          this.doForEachBetween(this.candidates, candidate, this.lastSelectedCandidate, this.selectCandidate);
        } else {
          this.lastSelectedCandidate = candidate;
          this.selectCandidate(candidate);
        }
      }
    },
    selectCandidate: function selectCandidate(candidate) {
      if (this.selectedLabel) {
        // Do not select candidates that have been converted.
        if (!candidate.annotation_id) {
          this.updateSelectCandidate(candidate, this.selectedLabel).then(this.maybeInitFocussedCandidate);
        }
      } else {
        _import__WEBPACK_IMPORTED_MODULE_11__.Messages.info('Please select a label first.');
      }
    },
    unselectCandidate: function unselectCandidate(candidate) {
      var next = this.nextFocussedCandidate;
      this.updateSelectCandidate(candidate, null).bind(this).then(function () {
        this.maybeUnsetFocussedCandidate(candidate, next);
      });
    },
    updateSelectCandidate: function updateSelectCandidate(candidate, label) {
      var _this14 = this;

      var oldLabel = candidate.label;
      candidate.label = label;
      this.setSelectedCandidateId(candidate);
      var labelId = label ? label.id : null;
      var promise = _api_annotationCandidate__WEBPACK_IMPORTED_MODULE_0__["default"].update({
        id: candidate.id
      }, {
        label_id: labelId
      });
      promise["catch"](function (response) {
        (0,_import__WEBPACK_IMPORTED_MODULE_11__.handleErrorResponse)(response);
        candidate.label = oldLabel;

        _this14.setSelectedCandidateId(candidate);
      });
      return promise;
    },
    fetchCandidateAnnotations: function fetchCandidateAnnotations(id) {
      if (!this.candidateAnnotationCache.hasOwnProperty(id)) {
        this.candidateAnnotationCache[id] = _api_maiaJob__WEBPACK_IMPORTED_MODULE_2__["default"].getAnnotationCandidatePoints({
          jobId: this.job.id,
          imageId: id
        }).then(this.parseAnnotations);
      }

      return this.candidateAnnotationCache[id];
    },
    setCurrentCandidateImageAndAnnotations: function setCurrentCandidateImageAndAnnotations(args) {
      var _this15 = this;

      this.currentCandidateImage = args[0];
      this.currentCandidates = args[1];
      this.currentCandidatesById = {};
      this.currentCandidates.forEach(function (p) {
        return _this15.currentCandidatesById[p.id] = p;
      });
    },
    handlePreviousCandidateImage: function handlePreviousCandidateImage() {
      this.currentCandidateImageIndex = this.previousCandidateImageIndex;
    },
    handlePreviousCandidate: function handlePreviousCandidate() {
      if (this.previousFocussedCandidate) {
        this.focussedCandidate = this.previousFocussedCandidate;
      } else {
        this.handlePreviousCandidateImage();
      }
    },
    handleNextCandidateImage: function handleNextCandidateImage() {
      this.currentCandidateImageIndex = this.nextCandidateImageIndex;
    },
    handleNextCandidate: function handleNextCandidate() {
      if (this.nextFocussedCandidate) {
        this.focussedCandidate = this.nextFocussedCandidate;
      } else {
        this.handleNextCandidateImage();
      }
    },
    focusCandidateToShow: function focusCandidateToShow() {
      if (this.focussedCandidateToShow) {
        var p = this.currentCandidatesById[this.focussedCandidateToShow.id];

        if (p) {
          this.$refs.refineCandidatesCanvas.focusAnnotation(p, true, false);
        }
      }
    },
    maybeInitFocussedCandidate: function maybeInitFocussedCandidate() {
      if (!this.focussedCandidate && this.hasSelectedCandidates) {
        this.focussedCandidate = this.selectedCandidates[0];
      }
    },
    maybeUnsetFocussedCandidate: function maybeUnsetFocussedCandidate(proposal, next) {
      if (this.focussedCandidate && this.focussedCandidate.id === proposal.id) {
        if (next && next.id !== proposal.id) {
          this.focussedCandidate = next;
        } else {
          this.focussedCandidate = null;
        }
      }
    },
    handleSelectedLabel: function handleSelectedLabel(label) {
      this.selectedLabel = label;
    },
    doForEachBetween: function doForEachBetween(all, first, second, callback) {
      var index1 = all.indexOf(first);
      var index2 = all.indexOf(second); // The second element is exclusive so shift the lower index one up if
      // second is before first or the upper index down if second is after
      // first.

      if (index2 < index1) {
        var tmp = index2;
        index2 = index1;
        index1 = tmp + 1;
      } else {
        index2 -= 1;
      }

      for (var i = index1; i <= index2; i++) {
        callback(all[i]);
      }
    },
    cacheNextCandidateImage: function cacheNextCandidateImage() {
      // Do nothing if there is only one image.
      if (this.currentCandidateImageId !== this.nextFocussedCandidateImageId) {
        _import__WEBPACK_IMPORTED_MODULE_11__.ImagesStore.fetchImage(this.nextFocussedCandidateImageId) // Ignore errors in this case. The application will try to reload
        // the data again if the user switches to the respective image
        // and display the error message then.
        ["catch"](function () {});
      }
    },
    cacheNextCandidateAnnotations: function cacheNextCandidateAnnotations() {
      // Do nothing if there is only one image.
      if (this.currentCandidateImageId !== this.nextFocussedCandidateImageId) {
        this.fetchCandidateAnnotations(this.nextFocussedCandidateImageId) // Ignore errors in this case. The application will try to reload
        // the data again if the user switches to the respective image
        // and display the error message then.
        ["catch"](function () {});
      }
    },
    handleRefineCandidate: function handleRefineCandidate(candidates) {
      Vue.Promise.all(candidates.map(this.updateCandidatePoints))["catch"](_import__WEBPACK_IMPORTED_MODULE_11__.handleErrorResponse);
    },
    updateCandidatePoints: function updateCandidatePoints(candidate) {
      var toUpdate = this.currentCandidatesById[candidate.id];
      return _api_annotationCandidate__WEBPACK_IMPORTED_MODULE_0__["default"].update({
        id: candidate.id
      }, {
        points: candidate.points
      }).then(function () {
        return toUpdate.points = candidate.points;
      });
    },
    handleConvertCandidates: function handleConvertCandidates() {
      this.startLoading();
      _api_maiaJob__WEBPACK_IMPORTED_MODULE_2__["default"].convertAnnotationCandidates({
        id: this.job.id
      }, {}).then(this.waitForConvertedCandidates);
    },
    waitForConvertedCandidates: function waitForConvertedCandidates() {
      var _this16 = this;

      var wait = function wait() {
        window.setTimeout(function () {
          _api_maiaJob__WEBPACK_IMPORTED_MODULE_2__["default"].convertingAnnotationCandidates({
            id: _this16.job.id
          }).then(wait, _this16.handleConvertedCandidates);
        }, 2000);
      };

      wait();
    },
    handleConvertedCandidates: function handleConvertedCandidates(response) {
      var _this17 = this;

      if (response.status === 404) {
        return this.fetchCandidates(true).then(function () {
          var next = _this17.nextFocussedCandidate;

          _this17.candidates.map(function (c) {
            return _this17.maybeUnsetFocussedCandidate(c, next);
          });
        }).then(this.finishLoading);
      } else {
        (0,_import__WEBPACK_IMPORTED_MODULE_11__.handleErrorResponse)(response);
        this.finishLoading();
      }
    },
    getSequenceId: function getSequenceId() {
      return this.sequenceCounter++;
    }
  },
  watch: {
    selectProposalsTabOpen: function selectProposalsTabOpen(open) {
      this.visitedSelectProposalsTab = true;

      if (open) {
        _import__WEBPACK_IMPORTED_MODULE_11__.Keyboard.setActiveSet('select-proposals');
      }
    },
    refineProposalsTabOpen: function refineProposalsTabOpen(open) {
      this.visitedRefineProposalsTab = true;

      if (open) {
        _import__WEBPACK_IMPORTED_MODULE_11__.Keyboard.setActiveSet('refine-proposals');
        this.maybeInitFocussedProposal();
      }
    },
    selectCandidatesTabOpen: function selectCandidatesTabOpen(open) {
      this.visitedSelectCandidatesTab = true;

      if (open) {
        _import__WEBPACK_IMPORTED_MODULE_11__.Keyboard.setActiveSet('select-candidates');
      }
    },
    refineCandidatesTabOpen: function refineCandidatesTabOpen(open) {
      this.visitedRefineCandidatesTab = true;

      if (open) {
        _import__WEBPACK_IMPORTED_MODULE_11__.Keyboard.setActiveSet('refine-candidates');
      }
    },
    visitedSelectProposalsTab: function visitedSelectProposalsTab() {
      this.fetchProposals();
    },
    visitedRefineProposalsTab: function visitedRefineProposalsTab() {
      this.fetchProposals().then(this.maybeInitFocussedProposal).then(this.maybeInitCurrentProposalImage);
    },
    visitedSelectCandidatesTab: function visitedSelectCandidatesTab() {
      this.fetchCandidates();
    },
    visitedRefineCandidatesTab: function visitedRefineCandidatesTab() {
      this.fetchCandidates().then(this.maybeInitFocussedCandidate).then(this.maybeInitCurrentCandidateImage);
    },
    currentProposalImageId: function currentProposalImageId(id) {
      if (id) {
        this.startLoading();
        Vue.Promise.all([_import__WEBPACK_IMPORTED_MODULE_11__.ImagesStore.fetchAndDrawImage(id), this.fetchProposalAnnotations(id), this.fetchProposals()]).then(this.setCurrentProposalImageAndAnnotations).then(this.focusProposalToShow).then(this.cacheNextProposalImage).then(this.cacheNextProposalAnnotations)["catch"](this.handleLoadingError)["finally"](this.finishLoading);
      } else {
        this.setCurrentProposalImageAndAnnotations([null, null]);
      }
    },
    focussedProposalToShow: function focussedProposalToShow(proposal, old) {
      if (proposal) {
        if (old && old.image_id === proposal.image_id) {
          this.focusProposalToShow();
        } else {
          this.currentProposalImageIndex = this.proposalImageIds.indexOf(proposal.image_id);
        }

        this.setSeenProposalId(proposal);
      }
    },
    currentCandidateImageId: function currentCandidateImageId(id) {
      if (id) {
        this.startLoading();
        Vue.Promise.all([_import__WEBPACK_IMPORTED_MODULE_11__.ImagesStore.fetchAndDrawImage(id), this.fetchCandidateAnnotations(id), this.fetchCandidates()]).then(this.setCurrentCandidateImageAndAnnotations).then(this.focusCandidateToShow).then(this.cacheNextCandidateImage).then(this.cacheNextCandidateAnnotations)["catch"](this.handleLoadingError)["finally"](this.finishLoading);
      } else {
        this.setCurrentCandidateImageAndAnnotations([null, null]);
      }
    },
    focussedCandidateToShow: function focussedCandidateToShow(candidate, old) {
      if (candidate) {
        if (old && old.image_id === candidate.image_id) {
          this.focusCandidateToShow();
        } else {
          this.currentCandidateImageIndex = this.candidateImageIds.indexOf(candidate.image_id);
        }
      }
    }
  },
  created: function created() {
    this.job = biigle.$require('maia.job');
    this.states = biigle.$require('maia.states');
    this.labelTrees = biigle.$require('maia.labelTrees');
  }
});

/***/ }),

/***/ "./src/resources/assets/js/api/annotationCandidate.js":
/*!************************************************************!*\
  !*** ./src/resources/assets/js/api/annotationCandidate.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Resource for annotation candidates.
 *
 * let resource = biigle.$require('maia.api.annotationCandidate');
 *
 * Update the candidate:
 * resource.update({id: 1}, {label_id: 123}).then(...);
 *
 * @type {Vue.resource}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Vue.resource('api/v1/maia/annotation-candidates{/id}'));

/***/ }),

/***/ "./src/resources/assets/js/api/areaKnowledgeTransferVolume.js":
/*!********************************************************************!*\
  !*** ./src/resources/assets/js/api/areaKnowledgeTransferVolume.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Resource for (area) knowledge transfer volumes.
 *
 * @type {Vue.resource}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Vue.resource('api/v1/volumes/filter/area-knowledge-transfer'));

/***/ }),

/***/ "./src/resources/assets/js/api/knowledgeTransferVolume.js":
/*!****************************************************************!*\
  !*** ./src/resources/assets/js/api/knowledgeTransferVolume.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Resource for (distance) knowledge transfer volumes.
 *
 * @type {Vue.resource}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Vue.resource('api/v1/volumes/filter/knowledge-transfer'));

/***/ }),

/***/ "./src/resources/assets/js/api/maiaJob.js":
/*!************************************************!*\
  !*** ./src/resources/assets/js/api/maiaJob.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Resource for MAIA jobs.
 *
 * let resource = biigle.$require('maia.api.maiaJob');
 *
 * Create a MAIA job:
 * resource.save({id: volumeId}, {
 *     clusters: 5,
 *     patch_size: 39,
 *     ...
 * }).then(...);
 *
 * Get all training proposals of a job:
 * resource.getTrainingProposals({id: 1}).then(...);
 *
 * Get coordinates of training proposals for an image belonging to the job:
 * resource.getTrainingProposalPoints({jobId: 1, imageId: 2}).then(...);
 *
 * Delete a MAIA job:
 * resource.delete({id: 1}).then(...);
 *
 * @type {Vue.resource}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Vue.resource('api/v1/maia-jobs{/id}', {}, {
  save: {
    method: 'POST',
    url: 'api/v1/volumes{/id}/maia-jobs'
  },
  getTrainingProposals: {
    method: 'GET',
    url: 'api/v1/maia-jobs{/id}/training-proposals'
  },
  getTrainingProposalPoints: {
    method: 'GET',
    url: 'api/v1/maia-jobs{/jobId}/images{/imageId}/training-proposals'
  },
  getAnnotationCandidates: {
    method: 'GET',
    url: 'api/v1/maia-jobs{/id}/annotation-candidates'
  },
  getAnnotationCandidatePoints: {
    method: 'GET',
    url: 'api/v1/maia-jobs{/jobId}/images{/imageId}/annotation-candidates'
  },
  convertAnnotationCandidates: {
    method: 'POST',
    url: 'api/v1/maia-jobs{/id}/annotation-candidates'
  },
  convertingAnnotationCandidates: {
    method: 'GET',
    url: 'api/v1/maia-jobs{/id}/converting-candidates'
  }
}));

/***/ }),

/***/ "./src/resources/assets/js/api/trainingProposal.js":
/*!*********************************************************!*\
  !*** ./src/resources/assets/js/api/trainingProposal.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Resource for training proposals.
 *
 * let resource = biigle.$require('maia.api.trainingProposal');
 *
 * Update the proposal:
 * resource.update({id: 1}, {selected: true}).then(...);
 *
 * @type {Vue.resource}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Vue.resource('api/v1/maia/training-proposals{/id}'));

/***/ }),

/***/ "./src/resources/assets/js/import.js":
/*!*******************************************!*\
  !*** ./src/resources/assets/js/import.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "AnnotationCanvas": () => (/* binding */ AnnotationCanvas),
/* harmony export */   "AnnotationPatch": () => (/* binding */ AnnotationPatch),
/* harmony export */   "AttachLabelInteraction": () => (/* binding */ AttachLabelInteraction),
/* harmony export */   "handleErrorResponse": () => (/* binding */ handleErrorResponse),
/* harmony export */   "ImageGrid": () => (/* binding */ ImageGrid),
/* harmony export */   "ImageGridImage": () => (/* binding */ ImageGridImage),
/* harmony export */   "ImagesStore": () => (/* binding */ ImagesStore),
/* harmony export */   "Keyboard": () => (/* binding */ Keyboard),
/* harmony export */   "LabelTrees": () => (/* binding */ LabelTrees),
/* harmony export */   "LabelTypeahead": () => (/* binding */ LabelTypeahead),
/* harmony export */   "LoaderMixin": () => (/* binding */ LoaderMixin),
/* harmony export */   "Messages": () => (/* binding */ Messages),
/* harmony export */   "Sidebar": () => (/* binding */ Sidebar),
/* harmony export */   "SidebarTab": () => (/* binding */ SidebarTab),
/* harmony export */   "StylesStore": () => (/* binding */ StylesStore)
/* harmony export */ });
var AnnotationCanvas = biigle.$require('annotations.components.annotationCanvas');
var AnnotationPatch = biigle.$require('largo.mixins.annotationPatch');
var AttachLabelInteraction = biigle.$require('annotations.ol.AttachLabelInteraction');
var handleErrorResponse = biigle.$require('messages').handleErrorResponse;
var ImageGrid = biigle.$require('volumes.components.imageGrid');
var ImageGridImage = biigle.$require('volumes.components.imageGridImage');
var ImagesStore = biigle.$require('annotations.stores.images');
var Keyboard = biigle.$require('keyboard');
var LabelTrees = biigle.$require('labelTrees.components.labelTrees');
var LabelTypeahead = biigle.$require('labelTrees.components.labelTypeahead');
var LoaderMixin = biigle.$require('core.mixins.loader');
var Messages = biigle.$require('messages');
var Sidebar = biigle.$require('core.components.sidebar');
var SidebarTab = biigle.$require('core.components.sidebarTab');
var StylesStore = biigle.$require('annotations.stores.styles');

/***/ }),

/***/ "./src/resources/assets/js/main.js":
/*!*****************************************!*\
  !*** ./src/resources/assets/js/main.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _form__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./form */ "./src/resources/assets/js/form.vue");
/* harmony import */ var _show__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./show */ "./src/resources/assets/js/show.vue");


biigle.$mount('maia-job-form', _form__WEBPACK_IMPORTED_MODULE_0__["default"]);
biigle.$mount('maia-show-container', _show__WEBPACK_IMPORTED_MODULE_1__["default"]);

/***/ }),

/***/ "./src/resources/assets/sass/main.scss":
/*!*********************************************!*\
  !*** ./src/resources/assets/sass/main.scss ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./node_modules/ol/AssertionError.js":
/*!*******************************************!*\
  !*** ./node_modules/ol/AssertionError.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./util.js */ "./node_modules/ol/util.js");
/**
 * @module ol/AssertionError
 */


/**
 * Error object thrown when an assertion failed. This is an ECMA-262 Error,
 * extended with a `code` property.
 * See https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Error.
 */
var AssertionError = /*@__PURE__*/(function (Error) {
  function AssertionError(code) {
    var path = _util_js__WEBPACK_IMPORTED_MODULE_0__.VERSION === 'latest' ? _util_js__WEBPACK_IMPORTED_MODULE_0__.VERSION : 'v' + _util_js__WEBPACK_IMPORTED_MODULE_0__.VERSION.split('-')[0];
    var message = 'Assertion failed. See https://openlayers.org/en/' + path +
    '/doc/errors/#' + code + ' for details.';

    Error.call(this, message);

    /**
     * Error code. The meaning of the code can be found on
     * https://openlayers.org/en/latest/doc/errors/ (replace `latest` with
     * the version found in the OpenLayers script's header comment if a version
     * other than the latest is used).
     * @type {number}
     * @api
     */
    this.code = code;

    /**
     * @type {string}
     */
    this.name = 'AssertionError';

    // Re-assign message, see https://github.com/Rich-Harris/buble/issues/40
    this.message = message;
  }

  if ( Error ) AssertionError.__proto__ = Error;
  AssertionError.prototype = Object.create( Error && Error.prototype );
  AssertionError.prototype.constructor = AssertionError;

  return AssertionError;
}(Error));

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AssertionError);

//# sourceMappingURL=AssertionError.js.map

/***/ }),

/***/ "./node_modules/ol/Collection.js":
/*!***************************************!*\
  !*** ./node_modules/ol/Collection.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "CollectionEvent": () => (/* binding */ CollectionEvent),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AssertionError_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AssertionError.js */ "./node_modules/ol/AssertionError.js");
/* harmony import */ var _CollectionEventType_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CollectionEventType.js */ "./node_modules/ol/CollectionEventType.js");
/* harmony import */ var _Object_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Object.js */ "./node_modules/ol/Object.js");
/* harmony import */ var _events_Event_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./events/Event.js */ "./node_modules/ol/events/Event.js");
/**
 * @module ol/Collection
 */






/**
 * @enum {string}
 * @private
 */
var Property = {
  LENGTH: 'length'
};


/**
 * @classdesc
 * Events emitted by {@link module:ol/Collection~Collection} instances are instances of this
 * type.
 */
var CollectionEvent = /*@__PURE__*/(function (Event) {
  function CollectionEvent(type, opt_element) {
    Event.call(this, type);

    /**
     * The element that is added to or removed from the collection.
     * @type {*}
     * @api
     */
    this.element = opt_element;

  }

  if ( Event ) CollectionEvent.__proto__ = Event;
  CollectionEvent.prototype = Object.create( Event && Event.prototype );
  CollectionEvent.prototype.constructor = CollectionEvent;

  return CollectionEvent;
}(_events_Event_js__WEBPACK_IMPORTED_MODULE_0__["default"]));


/**
 * @typedef {Object} Options
 * @property {boolean} [unique=false] Disallow the same item from being added to
 * the collection twice.
 */

/**
 * @classdesc
 * An expanded version of standard JS Array, adding convenience methods for
 * manipulation. Add and remove changes to the Collection trigger a Collection
 * event. Note that this does not cover changes to the objects _within_ the
 * Collection; they trigger events on the appropriate object, not on the
 * Collection as a whole.
 *
 * @fires CollectionEvent
 *
 * @template T
 * @api
 */
var Collection = /*@__PURE__*/(function (BaseObject) {
  function Collection(opt_array, opt_options) {

    BaseObject.call(this);

    var options = opt_options || {};

    /**
     * @private
     * @type {boolean}
     */
    this.unique_ = !!options.unique;

    /**
     * @private
     * @type {!Array<T>}
     */
    this.array_ = opt_array ? opt_array : [];

    if (this.unique_) {
      for (var i = 0, ii = this.array_.length; i < ii; ++i) {
        this.assertUnique_(this.array_[i], i);
      }
    }

    this.updateLength_();

  }

  if ( BaseObject ) Collection.__proto__ = BaseObject;
  Collection.prototype = Object.create( BaseObject && BaseObject.prototype );
  Collection.prototype.constructor = Collection;

  /**
   * Remove all elements from the collection.
   * @api
   */
  Collection.prototype.clear = function clear () {
    while (this.getLength() > 0) {
      this.pop();
    }
  };

  /**
   * Add elements to the collection.  This pushes each item in the provided array
   * to the end of the collection.
   * @param {!Array<T>} arr Array.
   * @return {Collection<T>} This collection.
   * @api
   */
  Collection.prototype.extend = function extend (arr) {
    for (var i = 0, ii = arr.length; i < ii; ++i) {
      this.push(arr[i]);
    }
    return this;
  };

  /**
   * Iterate over each element, calling the provided callback.
   * @param {function(T, number, Array<T>): *} f The function to call
   *     for every element. This function takes 3 arguments (the element, the
   *     index and the array). The return value is ignored.
   * @api
   */
  Collection.prototype.forEach = function forEach (f) {
    var array = this.array_;
    for (var i = 0, ii = array.length; i < ii; ++i) {
      f(array[i], i, array);
    }
  };

  /**
   * Get a reference to the underlying Array object. Warning: if the array
   * is mutated, no events will be dispatched by the collection, and the
   * collection's "length" property won't be in sync with the actual length
   * of the array.
   * @return {!Array<T>} Array.
   * @api
   */
  Collection.prototype.getArray = function getArray () {
    return this.array_;
  };

  /**
   * Get the element at the provided index.
   * @param {number} index Index.
   * @return {T} Element.
   * @api
   */
  Collection.prototype.item = function item (index) {
    return this.array_[index];
  };

  /**
   * Get the length of this collection.
   * @return {number} The length of the array.
   * @observable
   * @api
   */
  Collection.prototype.getLength = function getLength () {
    return this.get(Property.LENGTH);
  };

  /**
   * Insert an element at the provided index.
   * @param {number} index Index.
   * @param {T} elem Element.
   * @api
   */
  Collection.prototype.insertAt = function insertAt (index, elem) {
    if (this.unique_) {
      this.assertUnique_(elem);
    }
    this.array_.splice(index, 0, elem);
    this.updateLength_();
    this.dispatchEvent(
      new CollectionEvent(_CollectionEventType_js__WEBPACK_IMPORTED_MODULE_1__["default"].ADD, elem));
  };

  /**
   * Remove the last element of the collection and return it.
   * Return `undefined` if the collection is empty.
   * @return {T|undefined} Element.
   * @api
   */
  Collection.prototype.pop = function pop () {
    return this.removeAt(this.getLength() - 1);
  };

  /**
   * Insert the provided element at the end of the collection.
   * @param {T} elem Element.
   * @return {number} New length of the collection.
   * @api
   */
  Collection.prototype.push = function push (elem) {
    if (this.unique_) {
      this.assertUnique_(elem);
    }
    var n = this.getLength();
    this.insertAt(n, elem);
    return this.getLength();
  };

  /**
   * Remove the first occurrence of an element from the collection.
   * @param {T} elem Element.
   * @return {T|undefined} The removed element or undefined if none found.
   * @api
   */
  Collection.prototype.remove = function remove (elem) {
    var arr = this.array_;
    for (var i = 0, ii = arr.length; i < ii; ++i) {
      if (arr[i] === elem) {
        return this.removeAt(i);
      }
    }
    return undefined;
  };

  /**
   * Remove the element at the provided index and return it.
   * Return `undefined` if the collection does not contain this index.
   * @param {number} index Index.
   * @return {T|undefined} Value.
   * @api
   */
  Collection.prototype.removeAt = function removeAt (index) {
    var prev = this.array_[index];
    this.array_.splice(index, 1);
    this.updateLength_();
    this.dispatchEvent(new CollectionEvent(_CollectionEventType_js__WEBPACK_IMPORTED_MODULE_1__["default"].REMOVE, prev));
    return prev;
  };

  /**
   * Set the element at the provided index.
   * @param {number} index Index.
   * @param {T} elem Element.
   * @api
   */
  Collection.prototype.setAt = function setAt (index, elem) {
    var n = this.getLength();
    if (index < n) {
      if (this.unique_) {
        this.assertUnique_(elem, index);
      }
      var prev = this.array_[index];
      this.array_[index] = elem;
      this.dispatchEvent(
        new CollectionEvent(_CollectionEventType_js__WEBPACK_IMPORTED_MODULE_1__["default"].REMOVE, prev));
      this.dispatchEvent(
        new CollectionEvent(_CollectionEventType_js__WEBPACK_IMPORTED_MODULE_1__["default"].ADD, elem));
    } else {
      for (var j = n; j < index; ++j) {
        this.insertAt(j, undefined);
      }
      this.insertAt(index, elem);
    }
  };

  /**
   * @private
   */
  Collection.prototype.updateLength_ = function updateLength_ () {
    this.set(Property.LENGTH, this.array_.length);
  };

  /**
   * @private
   * @param {T} elem Element.
   * @param {number=} opt_except Optional index to ignore.
   */
  Collection.prototype.assertUnique_ = function assertUnique_ (elem, opt_except) {
    for (var i = 0, ii = this.array_.length; i < ii; ++i) {
      if (this.array_[i] === elem && i !== opt_except) {
        throw new _AssertionError_js__WEBPACK_IMPORTED_MODULE_2__["default"](58);
      }
    }
  };

  return Collection;
}(_Object_js__WEBPACK_IMPORTED_MODULE_3__["default"]));


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Collection);

//# sourceMappingURL=Collection.js.map

/***/ }),

/***/ "./node_modules/ol/CollectionEventType.js":
/*!************************************************!*\
  !*** ./node_modules/ol/CollectionEventType.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/CollectionEventType
 */

/**
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  /**
   * Triggered when an item is added to the collection.
   * @event module:ol/Collection.CollectionEvent#add
   * @api
   */
  ADD: 'add',
  /**
   * Triggered when an item is removed from the collection.
   * @event module:ol/Collection.CollectionEvent#remove
   * @api
   */
  REMOVE: 'remove'
});

//# sourceMappingURL=CollectionEventType.js.map

/***/ }),

/***/ "./node_modules/ol/Disposable.js":
/*!***************************************!*\
  !*** ./node_modules/ol/Disposable.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/Disposable
 */

/**
 * @classdesc
 * Objects that need to clean up after themselves.
 */
var Disposable = function Disposable() {
  /**
   * The object has already been disposed.
   * @type {boolean}
   * @private
   */
  this.disposed_ = false;
};

/**
 * Clean up.
 */
Disposable.prototype.dispose = function dispose () {
  if (!this.disposed_) {
    this.disposed_ = true;
    this.disposeInternal();
  }
};

/**
 * Extension point for disposable objects.
 * @protected
 */
Disposable.prototype.disposeInternal = function disposeInternal () {};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Disposable);

//# sourceMappingURL=Disposable.js.map

/***/ }),

/***/ "./node_modules/ol/ImageState.js":
/*!***************************************!*\
  !*** ./node_modules/ol/ImageState.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/ImageState
 */

/**
 * @enum {number}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  IDLE: 0,
  LOADING: 1,
  LOADED: 2,
  ERROR: 3
});

//# sourceMappingURL=ImageState.js.map

/***/ }),

/***/ "./node_modules/ol/LayerType.js":
/*!**************************************!*\
  !*** ./node_modules/ol/LayerType.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/LayerType
 */

/**
 * A layer type used when creating layer renderers.
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  IMAGE: 'IMAGE',
  TILE: 'TILE',
  VECTOR_TILE: 'VECTOR_TILE',
  VECTOR: 'VECTOR'
});

//# sourceMappingURL=LayerType.js.map

/***/ }),

/***/ "./node_modules/ol/Object.js":
/*!***********************************!*\
  !*** ./node_modules/ol/Object.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ObjectEvent": () => (/* binding */ ObjectEvent),
/* harmony export */   "getChangeEventType": () => (/* binding */ getChangeEventType),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./util.js */ "./node_modules/ol/util.js");
/* harmony import */ var _ObjectEventType_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ObjectEventType.js */ "./node_modules/ol/ObjectEventType.js");
/* harmony import */ var _Observable_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Observable.js */ "./node_modules/ol/Observable.js");
/* harmony import */ var _events_Event_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./events/Event.js */ "./node_modules/ol/events/Event.js");
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./obj.js */ "./node_modules/ol/obj.js");
/**
 * @module ol/Object
 */







/**
 * @classdesc
 * Events emitted by {@link module:ol/Object~BaseObject} instances are instances of this type.
 */
var ObjectEvent = /*@__PURE__*/(function (Event) {
  function ObjectEvent(type, key, oldValue) {
    Event.call(this, type);

    /**
     * The name of the property whose value is changing.
     * @type {string}
     * @api
     */
    this.key = key;

    /**
     * The old value. To get the new value use `e.target.get(e.key)` where
     * `e` is the event object.
     * @type {*}
     * @api
     */
    this.oldValue = oldValue;

  }

  if ( Event ) ObjectEvent.__proto__ = Event;
  ObjectEvent.prototype = Object.create( Event && Event.prototype );
  ObjectEvent.prototype.constructor = ObjectEvent;

  return ObjectEvent;
}(_events_Event_js__WEBPACK_IMPORTED_MODULE_0__["default"]));


/**
 * @classdesc
 * Abstract base class; normally only used for creating subclasses and not
 * instantiated in apps.
 * Most non-trivial classes inherit from this.
 *
 * This extends {@link module:ol/Observable} with observable
 * properties, where each property is observable as well as the object as a
 * whole.
 *
 * Classes that inherit from this have pre-defined properties, to which you can
 * add your owns. The pre-defined properties are listed in this documentation as
 * 'Observable Properties', and have their own accessors; for example,
 * {@link module:ol/Map~Map} has a `target` property, accessed with
 * `getTarget()` and changed with `setTarget()`. Not all properties are however
 * settable. There are also general-purpose accessors `get()` and `set()`. For
 * example, `get('target')` is equivalent to `getTarget()`.
 *
 * The `set` accessors trigger a change event, and you can monitor this by
 * registering a listener. For example, {@link module:ol/View~View} has a
 * `center` property, so `view.on('change:center', function(evt) {...});` would
 * call the function whenever the value of the center property changes. Within
 * the function, `evt.target` would be the view, so `evt.target.getCenter()`
 * would return the new center.
 *
 * You can add your own observable properties with
 * `object.set('prop', 'value')`, and retrieve that with `object.get('prop')`.
 * You can listen for changes on that property value with
 * `object.on('change:prop', listener)`. You can get a list of all
 * properties with {@link module:ol/Object~BaseObject#getProperties}.
 *
 * Note that the observable properties are separate from standard JS properties.
 * You can, for example, give your map object a title with
 * `map.title='New title'` and with `map.set('title', 'Another title')`. The
 * first will be a `hasOwnProperty`; the second will appear in
 * `getProperties()`. Only the second is observable.
 *
 * Properties can be deleted by using the unset method. E.g.
 * object.unset('foo').
 *
 * @fires ObjectEvent
 * @api
 */
var BaseObject = /*@__PURE__*/(function (Observable) {
  function BaseObject(opt_values) {
    Observable.call(this);

    // Call {@link module:ol/util~getUid} to ensure that the order of objects' ids is
    // the same as the order in which they were created.  This also helps to
    // ensure that object properties are always added in the same order, which
    // helps many JavaScript engines generate faster code.
    (0,_util_js__WEBPACK_IMPORTED_MODULE_1__.getUid)(this);

    /**
     * @private
     * @type {!Object<string, *>}
     */
    this.values_ = {};

    if (opt_values !== undefined) {
      this.setProperties(opt_values);
    }
  }

  if ( Observable ) BaseObject.__proto__ = Observable;
  BaseObject.prototype = Object.create( Observable && Observable.prototype );
  BaseObject.prototype.constructor = BaseObject;

  /**
   * Gets a value.
   * @param {string} key Key name.
   * @return {*} Value.
   * @api
   */
  BaseObject.prototype.get = function get (key) {
    var value;
    if (this.values_.hasOwnProperty(key)) {
      value = this.values_[key];
    }
    return value;
  };

  /**
   * Get a list of object property names.
   * @return {Array<string>} List of property names.
   * @api
   */
  BaseObject.prototype.getKeys = function getKeys () {
    return Object.keys(this.values_);
  };

  /**
   * Get an object of all property names and values.
   * @return {Object<string, *>} Object.
   * @api
   */
  BaseObject.prototype.getProperties = function getProperties () {
    return (0,_obj_js__WEBPACK_IMPORTED_MODULE_2__.assign)({}, this.values_);
  };

  /**
   * @param {string} key Key name.
   * @param {*} oldValue Old value.
   */
  BaseObject.prototype.notify = function notify (key, oldValue) {
    var eventType;
    eventType = getChangeEventType(key);
    this.dispatchEvent(new ObjectEvent(eventType, key, oldValue));
    eventType = _ObjectEventType_js__WEBPACK_IMPORTED_MODULE_3__["default"].PROPERTYCHANGE;
    this.dispatchEvent(new ObjectEvent(eventType, key, oldValue));
  };

  /**
   * Sets a value.
   * @param {string} key Key name.
   * @param {*} value Value.
   * @param {boolean=} opt_silent Update without triggering an event.
   * @api
   */
  BaseObject.prototype.set = function set (key, value, opt_silent) {
    if (opt_silent) {
      this.values_[key] = value;
    } else {
      var oldValue = this.values_[key];
      this.values_[key] = value;
      if (oldValue !== value) {
        this.notify(key, oldValue);
      }
    }
  };

  /**
   * Sets a collection of key-value pairs.  Note that this changes any existing
   * properties and adds new ones (it does not remove any existing properties).
   * @param {Object<string, *>} values Values.
   * @param {boolean=} opt_silent Update without triggering an event.
   * @api
   */
  BaseObject.prototype.setProperties = function setProperties (values, opt_silent) {
    for (var key in values) {
      this.set(key, values[key], opt_silent);
    }
  };

  /**
   * Unsets a property.
   * @param {string} key Key name.
   * @param {boolean=} opt_silent Unset without triggering an event.
   * @api
   */
  BaseObject.prototype.unset = function unset (key, opt_silent) {
    if (key in this.values_) {
      var oldValue = this.values_[key];
      delete this.values_[key];
      if (!opt_silent) {
        this.notify(key, oldValue);
      }
    }
  };

  return BaseObject;
}(_Observable_js__WEBPACK_IMPORTED_MODULE_4__["default"]));


/**
 * @type {Object<string, string>}
 */
var changeEventTypeCache = {};


/**
 * @param {string} key Key name.
 * @return {string} Change name.
 */
function getChangeEventType(key) {
  return changeEventTypeCache.hasOwnProperty(key) ?
    changeEventTypeCache[key] :
    (changeEventTypeCache[key] = 'change:' + key);
}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BaseObject);

//# sourceMappingURL=Object.js.map

/***/ }),

/***/ "./node_modules/ol/ObjectEventType.js":
/*!********************************************!*\
  !*** ./node_modules/ol/ObjectEventType.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/ObjectEventType
 */

/**
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  /**
   * Triggered when a property is changed.
   * @event module:ol/Object.ObjectEvent#propertychange
   * @api
   */
  PROPERTYCHANGE: 'propertychange'
});

//# sourceMappingURL=ObjectEventType.js.map

/***/ }),

/***/ "./node_modules/ol/Observable.js":
/*!***************************************!*\
  !*** ./node_modules/ol/Observable.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "unByKey": () => (/* binding */ unByKey),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _events_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./events.js */ "./node_modules/ol/events.js");
/* harmony import */ var _events_Target_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./events/Target.js */ "./node_modules/ol/events/Target.js");
/* harmony import */ var _events_EventType_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./events/EventType.js */ "./node_modules/ol/events/EventType.js");
/**
 * @module ol/Observable
 */




/**
 * @classdesc
 * Abstract base class; normally only used for creating subclasses and not
 * instantiated in apps.
 * An event target providing convenient methods for listener registration
 * and unregistration. A generic `change` event is always available through
 * {@link module:ol/Observable~Observable#changed}.
 *
 * @fires import("./events/Event.js").Event
 * @api
 */
var Observable = /*@__PURE__*/(function (EventTarget) {
  function Observable() {

    EventTarget.call(this);

    /**
     * @private
     * @type {number}
     */
    this.revision_ = 0;

  }

  if ( EventTarget ) Observable.__proto__ = EventTarget;
  Observable.prototype = Object.create( EventTarget && EventTarget.prototype );
  Observable.prototype.constructor = Observable;

  /**
   * Increases the revision counter and dispatches a 'change' event.
   * @api
   */
  Observable.prototype.changed = function changed () {
    ++this.revision_;
    this.dispatchEvent(_events_EventType_js__WEBPACK_IMPORTED_MODULE_0__["default"].CHANGE);
  };

  /**
   * Get the version number for this object.  Each time the object is modified,
   * its version number will be incremented.
   * @return {number} Revision.
   * @api
   */
  Observable.prototype.getRevision = function getRevision () {
    return this.revision_;
  };

  /**
   * Listen for a certain type of event.
   * @param {string|Array<string>} type The event type or array of event types.
   * @param {function(?): ?} listener The listener function.
   * @return {import("./events.js").EventsKey|Array<import("./events.js").EventsKey>} Unique key for the listener. If
   *     called with an array of event types as the first argument, the return
   *     will be an array of keys.
   * @api
   */
  Observable.prototype.on = function on (type, listener) {
    if (Array.isArray(type)) {
      var len = type.length;
      var keys = new Array(len);
      for (var i = 0; i < len; ++i) {
        keys[i] = (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.listen)(this, type[i], listener);
      }
      return keys;
    } else {
      return (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.listen)(this, /** @type {string} */ (type), listener);
    }
  };

  /**
   * Listen once for a certain type of event.
   * @param {string|Array<string>} type The event type or array of event types.
   * @param {function(?): ?} listener The listener function.
   * @return {import("./events.js").EventsKey|Array<import("./events.js").EventsKey>} Unique key for the listener. If
   *     called with an array of event types as the first argument, the return
   *     will be an array of keys.
   * @api
   */
  Observable.prototype.once = function once (type, listener) {
    if (Array.isArray(type)) {
      var len = type.length;
      var keys = new Array(len);
      for (var i = 0; i < len; ++i) {
        keys[i] = (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.listenOnce)(this, type[i], listener);
      }
      return keys;
    } else {
      return (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.listenOnce)(this, /** @type {string} */ (type), listener);
    }
  };

  /**
   * Unlisten for a certain type of event.
   * @param {string|Array<string>} type The event type or array of event types.
   * @param {function(?): ?} listener The listener function.
   * @api
   */
  Observable.prototype.un = function un (type, listener) {
    if (Array.isArray(type)) {
      for (var i = 0, ii = type.length; i < ii; ++i) {
        (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.unlisten)(this, type[i], listener);
      }
      return;
    } else {
      (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.unlisten)(this, /** @type {string} */ (type), listener);
    }
  };

  return Observable;
}(_events_Target_js__WEBPACK_IMPORTED_MODULE_2__["default"]));


/**
 * Removes an event listener using the key returned by `on()` or `once()`.
 * @param {import("./events.js").EventsKey|Array<import("./events.js").EventsKey>} key The key returned by `on()`
 *     or `once()` (or an array of keys).
 * @api
 */
function unByKey(key) {
  if (Array.isArray(key)) {
    for (var i = 0, ii = key.length; i < ii; ++i) {
      (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.unlistenByKey)(key[i]);
    }
  } else {
    (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.unlistenByKey)(/** @type {import("./events.js").EventsKey} */ (key));
  }
}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Observable);

//# sourceMappingURL=Observable.js.map

/***/ }),

/***/ "./node_modules/ol/array.js":
/*!**********************************!*\
  !*** ./node_modules/ol/array.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "binarySearch": () => (/* binding */ binarySearch),
/* harmony export */   "numberSafeCompareFunction": () => (/* binding */ numberSafeCompareFunction),
/* harmony export */   "includes": () => (/* binding */ includes),
/* harmony export */   "linearFindNearest": () => (/* binding */ linearFindNearest),
/* harmony export */   "reverseSubArray": () => (/* binding */ reverseSubArray),
/* harmony export */   "extend": () => (/* binding */ extend),
/* harmony export */   "remove": () => (/* binding */ remove),
/* harmony export */   "find": () => (/* binding */ find),
/* harmony export */   "equals": () => (/* binding */ equals),
/* harmony export */   "stableSort": () => (/* binding */ stableSort),
/* harmony export */   "findIndex": () => (/* binding */ findIndex),
/* harmony export */   "isSorted": () => (/* binding */ isSorted)
/* harmony export */ });
/**
 * @module ol/array
 */


/**
 * Performs a binary search on the provided sorted list and returns the index of the item if found. If it can't be found it'll return -1.
 * https://github.com/darkskyapp/binary-search
 *
 * @param {Array<*>} haystack Items to search through.
 * @param {*} needle The item to look for.
 * @param {Function=} opt_comparator Comparator function.
 * @return {number} The index of the item if found, -1 if not.
 */
function binarySearch(haystack, needle, opt_comparator) {
  var mid, cmp;
  var comparator = opt_comparator || numberSafeCompareFunction;
  var low = 0;
  var high = haystack.length;
  var found = false;

  while (low < high) {
    /* Note that "(low + high) >>> 1" may overflow, and results in a typecast
     * to double (which gives the wrong results). */
    mid = low + (high - low >> 1);
    cmp = +comparator(haystack[mid], needle);

    if (cmp < 0.0) { /* Too low. */
      low = mid + 1;

    } else { /* Key found or too high */
      high = mid;
      found = !cmp;
    }
  }

  /* Key not found. */
  return found ? low : ~low;
}


/**
 * Compare function for array sort that is safe for numbers.
 * @param {*} a The first object to be compared.
 * @param {*} b The second object to be compared.
 * @return {number} A negative number, zero, or a positive number as the first
 *     argument is less than, equal to, or greater than the second.
 */
function numberSafeCompareFunction(a, b) {
  return a > b ? 1 : a < b ? -1 : 0;
}


/**
 * Whether the array contains the given object.
 * @param {Array<*>} arr The array to test for the presence of the element.
 * @param {*} obj The object for which to test.
 * @return {boolean} The object is in the array.
 */
function includes(arr, obj) {
  return arr.indexOf(obj) >= 0;
}


/**
 * @param {Array<number>} arr Array.
 * @param {number} target Target.
 * @param {number} direction 0 means return the nearest, > 0
 *    means return the largest nearest, < 0 means return the
 *    smallest nearest.
 * @return {number} Index.
 */
function linearFindNearest(arr, target, direction) {
  var n = arr.length;
  if (arr[0] <= target) {
    return 0;
  } else if (target <= arr[n - 1]) {
    return n - 1;
  } else {
    var i;
    if (direction > 0) {
      for (i = 1; i < n; ++i) {
        if (arr[i] < target) {
          return i - 1;
        }
      }
    } else if (direction < 0) {
      for (i = 1; i < n; ++i) {
        if (arr[i] <= target) {
          return i;
        }
      }
    } else {
      for (i = 1; i < n; ++i) {
        if (arr[i] == target) {
          return i;
        } else if (arr[i] < target) {
          if (arr[i - 1] - target < target - arr[i]) {
            return i - 1;
          } else {
            return i;
          }
        }
      }
    }
    return n - 1;
  }
}


/**
 * @param {Array<*>} arr Array.
 * @param {number} begin Begin index.
 * @param {number} end End index.
 */
function reverseSubArray(arr, begin, end) {
  while (begin < end) {
    var tmp = arr[begin];
    arr[begin] = arr[end];
    arr[end] = tmp;
    ++begin;
    --end;
  }
}


/**
 * @param {Array<VALUE>} arr The array to modify.
 * @param {!Array<VALUE>|VALUE} data The elements or arrays of elements to add to arr.
 * @template VALUE
 */
function extend(arr, data) {
  var extension = Array.isArray(data) ? data : [data];
  var length = extension.length;
  for (var i = 0; i < length; i++) {
    arr[arr.length] = extension[i];
  }
}


/**
 * @param {Array<VALUE>} arr The array to modify.
 * @param {VALUE} obj The element to remove.
 * @template VALUE
 * @return {boolean} If the element was removed.
 */
function remove(arr, obj) {
  var i = arr.indexOf(obj);
  var found = i > -1;
  if (found) {
    arr.splice(i, 1);
  }
  return found;
}


/**
 * @param {Array<VALUE>} arr The array to search in.
 * @param {function(VALUE, number, ?) : boolean} func The function to compare.
 * @template VALUE
 * @return {VALUE|null} The element found or null.
 */
function find(arr, func) {
  var length = arr.length >>> 0;
  var value;

  for (var i = 0; i < length; i++) {
    value = arr[i];
    if (func(value, i, arr)) {
      return value;
    }
  }
  return null;
}


/**
 * @param {Array|Uint8ClampedArray} arr1 The first array to compare.
 * @param {Array|Uint8ClampedArray} arr2 The second array to compare.
 * @return {boolean} Whether the two arrays are equal.
 */
function equals(arr1, arr2) {
  var len1 = arr1.length;
  if (len1 !== arr2.length) {
    return false;
  }
  for (var i = 0; i < len1; i++) {
    if (arr1[i] !== arr2[i]) {
      return false;
    }
  }
  return true;
}


/**
 * Sort the passed array such that the relative order of equal elements is preverved.
 * See https://en.wikipedia.org/wiki/Sorting_algorithm#Stability for details.
 * @param {Array<*>} arr The array to sort (modifies original).
 * @param {!function(*, *): number} compareFnc Comparison function.
 * @api
 */
function stableSort(arr, compareFnc) {
  var length = arr.length;
  var tmp = Array(arr.length);
  var i;
  for (i = 0; i < length; i++) {
    tmp[i] = {index: i, value: arr[i]};
  }
  tmp.sort(function(a, b) {
    return compareFnc(a.value, b.value) || a.index - b.index;
  });
  for (i = 0; i < arr.length; i++) {
    arr[i] = tmp[i].value;
  }
}


/**
 * @param {Array<*>} arr The array to search in.
 * @param {Function} func Comparison function.
 * @return {number} Return index.
 */
function findIndex(arr, func) {
  var index;
  var found = !arr.every(function(el, idx) {
    index = idx;
    return !func(el, idx, arr);
  });
  return found ? index : -1;
}


/**
 * @param {Array<*>} arr The array to test.
 * @param {Function=} opt_func Comparison function.
 * @param {boolean=} opt_strict Strictly sorted (default false).
 * @return {boolean} Return index.
 */
function isSorted(arr, opt_func, opt_strict) {
  var compare = opt_func || numberSafeCompareFunction;
  return arr.every(function(currentVal, index) {
    if (index === 0) {
      return true;
    }
    var res = compare(arr[index - 1], currentVal);
    return !(res > 0 || opt_strict && res === 0);
  });
}

//# sourceMappingURL=array.js.map

/***/ }),

/***/ "./node_modules/ol/asserts.js":
/*!************************************!*\
  !*** ./node_modules/ol/asserts.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "assert": () => (/* binding */ assert)
/* harmony export */ });
/* harmony import */ var _AssertionError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssertionError.js */ "./node_modules/ol/AssertionError.js");
/**
 * @module ol/asserts
 */


/**
 * @param {*} assertion Assertion we expected to be truthy.
 * @param {number} errorCode Error code.
 */
function assert(assertion, errorCode) {
  if (!assertion) {
    throw new _AssertionError_js__WEBPACK_IMPORTED_MODULE_0__["default"](errorCode);
  }
}

//# sourceMappingURL=asserts.js.map

/***/ }),

/***/ "./node_modules/ol/color.js":
/*!**********************************!*\
  !*** ./node_modules/ol/color.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "asString": () => (/* binding */ asString),
/* harmony export */   "fromString": () => (/* binding */ fromString),
/* harmony export */   "asArray": () => (/* binding */ asArray),
/* harmony export */   "normalize": () => (/* binding */ normalize),
/* harmony export */   "toString": () => (/* binding */ toString)
/* harmony export */ });
/* harmony import */ var _asserts_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./asserts.js */ "./node_modules/ol/asserts.js");
/* harmony import */ var _math_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./math.js */ "./node_modules/ol/math.js");
/**
 * @module ol/color
 */




/**
 * A color represented as a short array [red, green, blue, alpha].
 * red, green, and blue should be integers in the range 0..255 inclusive.
 * alpha should be a float in the range 0..1 inclusive. If no alpha value is
 * given then `1` will be used.
 * @typedef {Array<number>} Color
 * @api
 */


/**
 * This RegExp matches # followed by 3, 4, 6, or 8 hex digits.
 * @const
 * @type {RegExp}
 * @private
 */
var HEX_COLOR_RE_ = /^#([a-f0-9]{3}|[a-f0-9]{4}(?:[a-f0-9]{2}){0,2})$/i;


/**
 * Regular expression for matching potential named color style strings.
 * @const
 * @type {RegExp}
 * @private
 */
var NAMED_COLOR_RE_ = /^([a-z]*)$/i;


/**
 * Return the color as an rgba string.
 * @param {Color|string} color Color.
 * @return {string} Rgba string.
 * @api
 */
function asString(color) {
  if (typeof color === 'string') {
    return color;
  } else {
    return toString(color);
  }
}

/**
 * Return named color as an rgba string.
 * @param {string} color Named color.
 * @return {string} Rgb string.
 */
function fromNamed(color) {
  var el = document.createElement('div');
  el.style.color = color;
  if (el.style.color !== '') {
    document.body.appendChild(el);
    var rgb = getComputedStyle(el).color;
    document.body.removeChild(el);
    return rgb;
  } else {
    return '';
  }
}


/**
 * @param {string} s String.
 * @return {Color} Color.
 */
var fromString = (
  function() {

    // We maintain a small cache of parsed strings.  To provide cheap LRU-like
    // semantics, whenever the cache grows too large we simply delete an
    // arbitrary 25% of the entries.

    /**
     * @const
     * @type {number}
     */
    var MAX_CACHE_SIZE = 1024;

    /**
     * @type {Object<string, Color>}
     */
    var cache = {};

    /**
     * @type {number}
     */
    var cacheSize = 0;

    return (
      /**
       * @param {string} s String.
       * @return {Color} Color.
       */
      function(s) {
        var color;
        if (cache.hasOwnProperty(s)) {
          color = cache[s];
        } else {
          if (cacheSize >= MAX_CACHE_SIZE) {
            var i = 0;
            for (var key in cache) {
              if ((i++ & 3) === 0) {
                delete cache[key];
                --cacheSize;
              }
            }
          }
          color = fromStringInternal_(s);
          cache[s] = color;
          ++cacheSize;
        }
        return color;
      }
    );

  })();

/**
 * Return the color as an array. This function maintains a cache of calculated
 * arrays which means the result should not be modified.
 * @param {Color|string} color Color.
 * @return {Color} Color.
 * @api
 */
function asArray(color) {
  if (Array.isArray(color)) {
    return color;
  } else {
    return fromString(color);
  }
}

/**
 * @param {string} s String.
 * @private
 * @return {Color} Color.
 */
function fromStringInternal_(s) {
  var r, g, b, a, color;

  if (NAMED_COLOR_RE_.exec(s)) {
    s = fromNamed(s);
  }

  if (HEX_COLOR_RE_.exec(s)) { // hex
    var n = s.length - 1; // number of hex digits
    var d; // number of digits per channel
    if (n <= 4) {
      d = 1;
    } else {
      d = 2;
    }
    var hasAlpha = n === 4 || n === 8;
    r = parseInt(s.substr(1 + 0 * d, d), 16);
    g = parseInt(s.substr(1 + 1 * d, d), 16);
    b = parseInt(s.substr(1 + 2 * d, d), 16);
    if (hasAlpha) {
      a = parseInt(s.substr(1 + 3 * d, d), 16);
    } else {
      a = 255;
    }
    if (d == 1) {
      r = (r << 4) + r;
      g = (g << 4) + g;
      b = (b << 4) + b;
      if (hasAlpha) {
        a = (a << 4) + a;
      }
    }
    color = [r, g, b, a / 255];
  } else if (s.indexOf('rgba(') == 0) { // rgba()
    color = s.slice(5, -1).split(',').map(Number);
    normalize(color);
  } else if (s.indexOf('rgb(') == 0) { // rgb()
    color = s.slice(4, -1).split(',').map(Number);
    color.push(1);
    normalize(color);
  } else {
    (0,_asserts_js__WEBPACK_IMPORTED_MODULE_0__.assert)(false, 14); // Invalid color
  }
  return color;
}


/**
 * TODO this function is only used in the test, we probably shouldn't export it
 * @param {Color} color Color.
 * @return {Color} Clamped color.
 */
function normalize(color) {
  color[0] = (0,_math_js__WEBPACK_IMPORTED_MODULE_1__.clamp)((color[0] + 0.5) | 0, 0, 255);
  color[1] = (0,_math_js__WEBPACK_IMPORTED_MODULE_1__.clamp)((color[1] + 0.5) | 0, 0, 255);
  color[2] = (0,_math_js__WEBPACK_IMPORTED_MODULE_1__.clamp)((color[2] + 0.5) | 0, 0, 255);
  color[3] = (0,_math_js__WEBPACK_IMPORTED_MODULE_1__.clamp)(color[3], 0, 1);
  return color;
}


/**
 * @param {Color} color Color.
 * @return {string} String.
 */
function toString(color) {
  var r = color[0];
  if (r != (r | 0)) {
    r = (r + 0.5) | 0;
  }
  var g = color[1];
  if (g != (g | 0)) {
    g = (g + 0.5) | 0;
  }
  var b = color[2];
  if (b != (b | 0)) {
    b = (b + 0.5) | 0;
  }
  var a = color[3] === undefined ? 1 : color[3];
  return 'rgba(' + r + ',' + g + ',' + b + ',' + a + ')';
}

//# sourceMappingURL=color.js.map

/***/ }),

/***/ "./node_modules/ol/colorlike.js":
/*!**************************************!*\
  !*** ./node_modules/ol/colorlike.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "asColorLike": () => (/* binding */ asColorLike)
/* harmony export */ });
/* harmony import */ var _color_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./color.js */ "./node_modules/ol/color.js");
/**
 * @module ol/colorlike
 */



/**
 * A type accepted by CanvasRenderingContext2D.fillStyle
 * or CanvasRenderingContext2D.strokeStyle.
 * Represents a color, pattern, or gradient. The origin for patterns and
 * gradients as fill style is an increment of 512 css pixels from map coordinate
 * `[0, 0]`. For seamless repeat patterns, width and height of the pattern image
 * must be a factor of two (2, 4, 8, ..., 512).
 *
 * @typedef {string|CanvasPattern|CanvasGradient} ColorLike
 * @api
 */


/**
 * @param {import("./color.js").Color|ColorLike} color Color.
 * @return {ColorLike} The color as an {@link ol/colorlike~ColorLike}.
 * @api
 */
function asColorLike(color) {
  if (Array.isArray(color)) {
    return (0,_color_js__WEBPACK_IMPORTED_MODULE_0__.toString)(color);
  } else {
    return color;
  }
}

//# sourceMappingURL=colorlike.js.map

/***/ }),

/***/ "./node_modules/ol/css.js":
/*!********************************!*\
  !*** ./node_modules/ol/css.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "CLASS_HIDDEN": () => (/* binding */ CLASS_HIDDEN),
/* harmony export */   "CLASS_SELECTABLE": () => (/* binding */ CLASS_SELECTABLE),
/* harmony export */   "CLASS_UNSELECTABLE": () => (/* binding */ CLASS_UNSELECTABLE),
/* harmony export */   "CLASS_UNSUPPORTED": () => (/* binding */ CLASS_UNSUPPORTED),
/* harmony export */   "CLASS_CONTROL": () => (/* binding */ CLASS_CONTROL),
/* harmony export */   "CLASS_COLLAPSED": () => (/* binding */ CLASS_COLLAPSED),
/* harmony export */   "getFontFamilies": () => (/* binding */ getFontFamilies)
/* harmony export */ });
/**
 * @module ol/css
 */


/**
 * The CSS class for hidden feature.
 *
 * @const
 * @type {string}
 */
var CLASS_HIDDEN = 'ol-hidden';


/**
 * The CSS class that we'll give the DOM elements to have them selectable.
 *
 * @const
 * @type {string}
 */
var CLASS_SELECTABLE = 'ol-selectable';


/**
 * The CSS class that we'll give the DOM elements to have them unselectable.
 *
 * @const
 * @type {string}
 */
var CLASS_UNSELECTABLE = 'ol-unselectable';


/**
 * The CSS class for unsupported feature.
 *
 * @const
 * @type {string}
 */
var CLASS_UNSUPPORTED = 'ol-unsupported';


/**
 * The CSS class for controls.
 *
 * @const
 * @type {string}
 */
var CLASS_CONTROL = 'ol-control';


/**
 * The CSS class that we'll give the DOM elements that are collapsed, i.e.
 * to those elements which usually can be expanded.
 *
 * @const
 * @type {string}
 */
var CLASS_COLLAPSED = 'ol-collapsed';


/**
 * Get the list of font families from a font spec.  Note that this doesn't work
 * for font families that have commas in them.
 * @param {string} The CSS font property.
 * @return {Object<string>} The font families (or null if the input spec is invalid).
 */
var getFontFamilies = (function() {
  var style;
  var cache = {};
  return function(font) {
    if (!style) {
      style = document.createElement('div').style;
    }
    if (!(font in cache)) {
      style.font = font;
      var family = style.fontFamily;
      style.font = '';
      if (!family) {
        return null;
      }
      cache[font] = family.split(/,\s?/);
    }
    return cache[font];
  };
})();

//# sourceMappingURL=css.js.map

/***/ }),

/***/ "./node_modules/ol/dom.js":
/*!********************************!*\
  !*** ./node_modules/ol/dom.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "createCanvasContext2D": () => (/* binding */ createCanvasContext2D),
/* harmony export */   "outerWidth": () => (/* binding */ outerWidth),
/* harmony export */   "outerHeight": () => (/* binding */ outerHeight),
/* harmony export */   "replaceNode": () => (/* binding */ replaceNode),
/* harmony export */   "removeNode": () => (/* binding */ removeNode),
/* harmony export */   "removeChildren": () => (/* binding */ removeChildren)
/* harmony export */ });
/**
 * @module ol/dom
 */


/**
 * Create an html canvas element and returns its 2d context.
 * @param {number=} opt_width Canvas width.
 * @param {number=} opt_height Canvas height.
 * @return {CanvasRenderingContext2D} The context.
 */
function createCanvasContext2D(opt_width, opt_height) {
  var canvas = /** @type {HTMLCanvasElement} */ (document.createElement('canvas'));
  if (opt_width) {
    canvas.width = opt_width;
  }
  if (opt_height) {
    canvas.height = opt_height;
  }
  return /** @type {CanvasRenderingContext2D} */ (canvas.getContext('2d'));
}


/**
 * Get the current computed width for the given element including margin,
 * padding and border.
 * Equivalent to jQuery's `$(el).outerWidth(true)`.
 * @param {!HTMLElement} element Element.
 * @return {number} The width.
 */
function outerWidth(element) {
  var width = element.offsetWidth;
  var style = getComputedStyle(element);
  width += parseInt(style.marginLeft, 10) + parseInt(style.marginRight, 10);

  return width;
}


/**
 * Get the current computed height for the given element including margin,
 * padding and border.
 * Equivalent to jQuery's `$(el).outerHeight(true)`.
 * @param {!HTMLElement} element Element.
 * @return {number} The height.
 */
function outerHeight(element) {
  var height = element.offsetHeight;
  var style = getComputedStyle(element);
  height += parseInt(style.marginTop, 10) + parseInt(style.marginBottom, 10);

  return height;
}

/**
 * @param {Node} newNode Node to replace old node
 * @param {Node} oldNode The node to be replaced
 */
function replaceNode(newNode, oldNode) {
  var parent = oldNode.parentNode;
  if (parent) {
    parent.replaceChild(newNode, oldNode);
  }
}

/**
 * @param {Node} node The node to remove.
 * @returns {Node} The node that was removed or null.
 */
function removeNode(node) {
  return node && node.parentNode ? node.parentNode.removeChild(node) : null;
}

/**
 * @param {Node} node The node to remove the children from.
 */
function removeChildren(node) {
  while (node.lastChild) {
    node.removeChild(node.lastChild);
  }
}

//# sourceMappingURL=dom.js.map

/***/ }),

/***/ "./node_modules/ol/events.js":
/*!***********************************!*\
  !*** ./node_modules/ol/events.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "bindListener": () => (/* binding */ bindListener),
/* harmony export */   "findListener": () => (/* binding */ findListener),
/* harmony export */   "getListeners": () => (/* binding */ getListeners),
/* harmony export */   "listen": () => (/* binding */ listen),
/* harmony export */   "listenOnce": () => (/* binding */ listenOnce),
/* harmony export */   "unlisten": () => (/* binding */ unlisten),
/* harmony export */   "unlistenByKey": () => (/* binding */ unlistenByKey),
/* harmony export */   "unlistenAll": () => (/* binding */ unlistenAll)
/* harmony export */ });
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./obj.js */ "./node_modules/ol/obj.js");
/**
 * @module ol/events
 */



/**
 * Key to use with {@link module:ol/Observable~Observable#unByKey}.
 * @typedef {Object} EventsKey
 * @property {Object} [bindTo]
 * @property {ListenerFunction} [boundListener]
 * @property {boolean} callOnce
 * @property {number} [deleteIndex]
 * @property {ListenerFunction} listener
 * @property {import("./events/Target.js").EventTargetLike} target
 * @property {string} type
 * @api
 */


/**
 * Listener function. This function is called with an event object as argument.
 * When the function returns `false`, event propagation will stop.
 *
 * @typedef {function((Event|import("./events/Event.js").default)): (void|boolean)} ListenerFunction
 * @api
 */


/**
 * @param {EventsKey} listenerObj Listener object.
 * @return {ListenerFunction} Bound listener.
 */
function bindListener(listenerObj) {
  var boundListener = function(evt) {
    var listener = listenerObj.listener;
    var bindTo = listenerObj.bindTo || listenerObj.target;
    if (listenerObj.callOnce) {
      unlistenByKey(listenerObj);
    }
    return listener.call(bindTo, evt);
  };
  listenerObj.boundListener = boundListener;
  return boundListener;
}


/**
 * Finds the matching {@link module:ol/events~EventsKey} in the given listener
 * array.
 *
 * @param {!Array<!EventsKey>} listeners Array of listeners.
 * @param {!Function} listener The listener function.
 * @param {Object=} opt_this The `this` value inside the listener.
 * @param {boolean=} opt_setDeleteIndex Set the deleteIndex on the matching
 *     listener, for {@link module:ol/events~unlistenByKey}.
 * @return {EventsKey|undefined} The matching listener object.
 */
function findListener(listeners, listener, opt_this, opt_setDeleteIndex) {
  var listenerObj;
  for (var i = 0, ii = listeners.length; i < ii; ++i) {
    listenerObj = listeners[i];
    if (listenerObj.listener === listener &&
        listenerObj.bindTo === opt_this) {
      if (opt_setDeleteIndex) {
        listenerObj.deleteIndex = i;
      }
      return listenerObj;
    }
  }
  return undefined;
}


/**
 * @param {import("./events/Target.js").EventTargetLike} target Target.
 * @param {string} type Type.
 * @return {Array<EventsKey>|undefined} Listeners.
 */
function getListeners(target, type) {
  var listenerMap = getListenerMap(target);
  return listenerMap ? listenerMap[type] : undefined;
}


/**
 * Get the lookup of listeners.
 * @param {Object} target Target.
 * @param {boolean=} opt_create If a map should be created if it doesn't exist.
 * @return {!Object<string, Array<EventsKey>>} Map of
 *     listeners by event type.
 */
function getListenerMap(target, opt_create) {
  var listenerMap = target.ol_lm;
  if (!listenerMap && opt_create) {
    listenerMap = target.ol_lm = {};
  }
  return listenerMap;
}


/**
 * Remove the listener map from a target.
 * @param {Object} target Target.
 */
function removeListenerMap(target) {
  delete target.ol_lm;
}


/**
 * Clean up all listener objects of the given type.  All properties on the
 * listener objects will be removed, and if no listeners remain in the listener
 * map, it will be removed from the target.
 * @param {import("./events/Target.js").EventTargetLike} target Target.
 * @param {string} type Type.
 */
function removeListeners(target, type) {
  var listeners = getListeners(target, type);
  if (listeners) {
    for (var i = 0, ii = listeners.length; i < ii; ++i) {
      /** @type {import("./events/Target.js").default} */ (target).
        removeEventListener(type, listeners[i].boundListener);
      (0,_obj_js__WEBPACK_IMPORTED_MODULE_0__.clear)(listeners[i]);
    }
    listeners.length = 0;
    var listenerMap = getListenerMap(target);
    if (listenerMap) {
      delete listenerMap[type];
      if (Object.keys(listenerMap).length === 0) {
        removeListenerMap(target);
      }
    }
  }
}


/**
 * Registers an event listener on an event target. Inspired by
 * https://google.github.io/closure-library/api/source/closure/goog/events/events.js.src.html
 *
 * This function efficiently binds a `listener` to a `this` object, and returns
 * a key for use with {@link module:ol/events~unlistenByKey}.
 *
 * @param {import("./events/Target.js").EventTargetLike} target Event target.
 * @param {string} type Event type.
 * @param {ListenerFunction} listener Listener.
 * @param {Object=} opt_this Object referenced by the `this` keyword in the
 *     listener. Default is the `target`.
 * @param {boolean=} opt_once If true, add the listener as one-off listener.
 * @return {EventsKey} Unique key for the listener.
 */
function listen(target, type, listener, opt_this, opt_once) {
  var listenerMap = getListenerMap(target, true);
  var listeners = listenerMap[type];
  if (!listeners) {
    listeners = listenerMap[type] = [];
  }
  var listenerObj = findListener(listeners, listener, opt_this, false);
  if (listenerObj) {
    if (!opt_once) {
      // Turn one-off listener into a permanent one.
      listenerObj.callOnce = false;
    }
  } else {
    listenerObj = /** @type {EventsKey} */ ({
      bindTo: opt_this,
      callOnce: !!opt_once,
      listener: listener,
      target: target,
      type: type
    });
    /** @type {import("./events/Target.js").default} */ (target).
      addEventListener(type, bindListener(listenerObj));
    listeners.push(listenerObj);
  }

  return listenerObj;
}


/**
 * Registers a one-off event listener on an event target. Inspired by
 * https://google.github.io/closure-library/api/source/closure/goog/events/events.js.src.html
 *
 * This function efficiently binds a `listener` as self-unregistering listener
 * to a `this` object, and returns a key for use with
 * {@link module:ol/events~unlistenByKey} in case the listener needs to be
 * unregistered before it is called.
 *
 * When {@link module:ol/events~listen} is called with the same arguments after this
 * function, the self-unregistering listener will be turned into a permanent
 * listener.
 *
 * @param {import("./events/Target.js").EventTargetLike} target Event target.
 * @param {string} type Event type.
 * @param {ListenerFunction} listener Listener.
 * @param {Object=} opt_this Object referenced by the `this` keyword in the
 *     listener. Default is the `target`.
 * @return {EventsKey} Key for unlistenByKey.
 */
function listenOnce(target, type, listener, opt_this) {
  return listen(target, type, listener, opt_this, true);
}


/**
 * Unregisters an event listener on an event target. Inspired by
 * https://google.github.io/closure-library/api/source/closure/goog/events/events.js.src.html
 *
 * To return a listener, this function needs to be called with the exact same
 * arguments that were used for a previous {@link module:ol/events~listen} call.
 *
 * @param {import("./events/Target.js").EventTargetLike} target Event target.
 * @param {string} type Event type.
 * @param {ListenerFunction} listener Listener.
 * @param {Object=} opt_this Object referenced by the `this` keyword in the
 *     listener. Default is the `target`.
 */
function unlisten(target, type, listener, opt_this) {
  var listeners = getListeners(target, type);
  if (listeners) {
    var listenerObj = findListener(listeners, listener, opt_this, true);
    if (listenerObj) {
      unlistenByKey(listenerObj);
    }
  }
}


/**
 * Unregisters event listeners on an event target. Inspired by
 * https://google.github.io/closure-library/api/source/closure/goog/events/events.js.src.html
 *
 * The argument passed to this function is the key returned from
 * {@link module:ol/events~listen} or {@link module:ol/events~listenOnce}.
 *
 * @param {EventsKey} key The key.
 */
function unlistenByKey(key) {
  if (key && key.target) {
    /** @type {import("./events/Target.js").default} */ (key.target).
      removeEventListener(key.type, key.boundListener);
    var listeners = getListeners(key.target, key.type);
    if (listeners) {
      var i = 'deleteIndex' in key ? key.deleteIndex : listeners.indexOf(key);
      if (i !== -1) {
        listeners.splice(i, 1);
      }
      if (listeners.length === 0) {
        removeListeners(key.target, key.type);
      }
    }
    (0,_obj_js__WEBPACK_IMPORTED_MODULE_0__.clear)(key);
  }
}


/**
 * Unregisters all event listeners on an event target. Inspired by
 * https://google.github.io/closure-library/api/source/closure/goog/events/events.js.src.html
 *
 * @param {import("./events/Target.js").EventTargetLike} target Target.
 */
function unlistenAll(target) {
  var listenerMap = getListenerMap(target);
  if (listenerMap) {
    for (var type in listenerMap) {
      removeListeners(target, type);
    }
  }
}

//# sourceMappingURL=events.js.map

/***/ }),

/***/ "./node_modules/ol/events/Event.js":
/*!*****************************************!*\
  !*** ./node_modules/ol/events/Event.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "stopPropagation": () => (/* binding */ stopPropagation),
/* harmony export */   "preventDefault": () => (/* binding */ preventDefault),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/events/Event
 */

/**
 * @classdesc
 * Stripped down implementation of the W3C DOM Level 2 Event interface.
 * See https://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-interface.
 *
 * This implementation only provides `type` and `target` properties, and
 * `stopPropagation` and `preventDefault` methods. It is meant as base class
 * for higher level events defined in the library, and works with
 * {@link module:ol/events/Target~Target}.
 */
var Event = function Event(type) {

  /**
   * @type {boolean}
   */
  this.propagationStopped;

  /**
   * The event type.
   * @type {string}
   * @api
   */
  this.type = type;

  /**
   * The event target.
   * @type {Object}
   * @api
   */
  this.target = null;
};

/**
 * Stop event propagation.
 * @api
 */
Event.prototype.preventDefault = function preventDefault () {
  this.propagationStopped = true;
};

/**
 * Stop event propagation.
 * @api
 */
Event.prototype.stopPropagation = function stopPropagation () {
  this.propagationStopped = true;
};


/**
 * @param {Event|import("./Event.js").default} evt Event
 */
function stopPropagation(evt) {
  evt.stopPropagation();
}


/**
 * @param {Event|import("./Event.js").default} evt Event
 */
function preventDefault(evt) {
  evt.preventDefault();
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Event);

//# sourceMappingURL=Event.js.map

/***/ }),

/***/ "./node_modules/ol/events/EventType.js":
/*!*********************************************!*\
  !*** ./node_modules/ol/events/EventType.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/events/EventType
 */

/**
 * @enum {string}
 * @const
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  /**
   * Generic change event. Triggered when the revision counter is increased.
   * @event module:ol/events/Event~Event#change
   * @api
   */
  CHANGE: 'change',

  CLEAR: 'clear',
  CONTEXTMENU: 'contextmenu',
  CLICK: 'click',
  DBLCLICK: 'dblclick',
  DRAGENTER: 'dragenter',
  DRAGOVER: 'dragover',
  DROP: 'drop',
  ERROR: 'error',
  KEYDOWN: 'keydown',
  KEYPRESS: 'keypress',
  LOAD: 'load',
  MOUSEDOWN: 'mousedown',
  MOUSEMOVE: 'mousemove',
  MOUSEOUT: 'mouseout',
  MOUSEUP: 'mouseup',
  MOUSEWHEEL: 'mousewheel',
  MSPOINTERDOWN: 'MSPointerDown',
  RESIZE: 'resize',
  TOUCHSTART: 'touchstart',
  TOUCHMOVE: 'touchmove',
  TOUCHEND: 'touchend',
  WHEEL: 'wheel'
});

//# sourceMappingURL=EventType.js.map

/***/ }),

/***/ "./node_modules/ol/events/Target.js":
/*!******************************************!*\
  !*** ./node_modules/ol/events/Target.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Disposable_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Disposable.js */ "./node_modules/ol/Disposable.js");
/* harmony import */ var _events_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../events.js */ "./node_modules/ol/events.js");
/* harmony import */ var _functions_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../functions.js */ "./node_modules/ol/functions.js");
/* harmony import */ var _Event_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Event.js */ "./node_modules/ol/events/Event.js");
/**
 * @module ol/events/Target
 */






/**
 * @typedef {EventTarget|Target} EventTargetLike
 */


/**
 * @classdesc
 * A simplified implementation of the W3C DOM Level 2 EventTarget interface.
 * See https://www.w3.org/TR/2000/REC-DOM-Level-2-Events-20001113/events.html#Events-EventTarget.
 *
 * There are two important simplifications compared to the specification:
 *
 * 1. The handling of `useCapture` in `addEventListener` and
 *    `removeEventListener`. There is no real capture model.
 * 2. The handling of `stopPropagation` and `preventDefault` on `dispatchEvent`.
 *    There is no event target hierarchy. When a listener calls
 *    `stopPropagation` or `preventDefault` on an event object, it means that no
 *    more listeners after this one will be called. Same as when the listener
 *    returns false.
 */
var Target = /*@__PURE__*/(function (Disposable) {
  function Target() {

    Disposable.call(this);

    /**
     * @private
     * @type {!Object<string, number>}
     */
    this.pendingRemovals_ = {};

    /**
     * @private
     * @type {!Object<string, number>}
     */
    this.dispatching_ = {};

    /**
     * @private
     * @type {!Object<string, Array<import("../events.js").ListenerFunction>>}
     */
    this.listeners_ = {};

  }

  if ( Disposable ) Target.__proto__ = Disposable;
  Target.prototype = Object.create( Disposable && Disposable.prototype );
  Target.prototype.constructor = Target;

  /**
   * @param {string} type Type.
   * @param {import("../events.js").ListenerFunction} listener Listener.
   */
  Target.prototype.addEventListener = function addEventListener (type, listener) {
    var listeners = this.listeners_[type];
    if (!listeners) {
      listeners = this.listeners_[type] = [];
    }
    if (listeners.indexOf(listener) === -1) {
      listeners.push(listener);
    }
  };

  /**
   * Dispatches an event and calls all listeners listening for events
   * of this type. The event parameter can either be a string or an
   * Object with a `type` property.
   *
   * @param {{type: string,
   *     target: (EventTargetLike|undefined),
   *     propagationStopped: (boolean|undefined)}|
   *     import("./Event.js").default|string} event Event object.
   * @return {boolean|undefined} `false` if anyone called preventDefault on the
   *     event object or if any of the listeners returned false.
   * @api
   */
  Target.prototype.dispatchEvent = function dispatchEvent (event) {
    var evt = typeof event === 'string' ? new _Event_js__WEBPACK_IMPORTED_MODULE_0__["default"](event) : event;
    var type = evt.type;
    evt.target = this;
    var listeners = this.listeners_[type];
    var propagate;
    if (listeners) {
      if (!(type in this.dispatching_)) {
        this.dispatching_[type] = 0;
        this.pendingRemovals_[type] = 0;
      }
      ++this.dispatching_[type];
      for (var i = 0, ii = listeners.length; i < ii; ++i) {
        if (listeners[i].call(this, evt) === false || evt.propagationStopped) {
          propagate = false;
          break;
        }
      }
      --this.dispatching_[type];
      if (this.dispatching_[type] === 0) {
        var pendingRemovals = this.pendingRemovals_[type];
        delete this.pendingRemovals_[type];
        while (pendingRemovals--) {
          this.removeEventListener(type, _functions_js__WEBPACK_IMPORTED_MODULE_1__.VOID);
        }
        delete this.dispatching_[type];
      }
      return propagate;
    }
  };

  /**
   * @inheritDoc
   */
  Target.prototype.disposeInternal = function disposeInternal () {
    (0,_events_js__WEBPACK_IMPORTED_MODULE_2__.unlistenAll)(this);
  };

  /**
   * Get the listeners for a specified event type. Listeners are returned in the
   * order that they will be called in.
   *
   * @param {string} type Type.
   * @return {Array<import("../events.js").ListenerFunction>} Listeners.
   */
  Target.prototype.getListeners = function getListeners (type) {
    return this.listeners_[type];
  };

  /**
   * @param {string=} opt_type Type. If not provided,
   *     `true` will be returned if this event target has any listeners.
   * @return {boolean} Has listeners.
   */
  Target.prototype.hasListener = function hasListener (opt_type) {
    return opt_type ?
      opt_type in this.listeners_ :
      Object.keys(this.listeners_).length > 0;
  };

  /**
   * @param {string} type Type.
   * @param {import("../events.js").ListenerFunction} listener Listener.
   */
  Target.prototype.removeEventListener = function removeEventListener (type, listener) {
    var listeners = this.listeners_[type];
    if (listeners) {
      var index = listeners.indexOf(listener);
      if (type in this.pendingRemovals_) {
        // make listener a no-op, and remove later in #dispatchEvent()
        listeners[index] = _functions_js__WEBPACK_IMPORTED_MODULE_1__.VOID;
        ++this.pendingRemovals_[type];
      } else {
        listeners.splice(index, 1);
        if (listeners.length === 0) {
          delete this.listeners_[type];
        }
      }
    }
  };

  return Target;
}(_Disposable_js__WEBPACK_IMPORTED_MODULE_3__["default"]));


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Target);

//# sourceMappingURL=Target.js.map

/***/ }),

/***/ "./node_modules/ol/extent.js":
/*!***********************************!*\
  !*** ./node_modules/ol/extent.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "boundingExtent": () => (/* binding */ boundingExtent),
/* harmony export */   "buffer": () => (/* binding */ buffer),
/* harmony export */   "clone": () => (/* binding */ clone),
/* harmony export */   "closestSquaredDistanceXY": () => (/* binding */ closestSquaredDistanceXY),
/* harmony export */   "containsCoordinate": () => (/* binding */ containsCoordinate),
/* harmony export */   "containsExtent": () => (/* binding */ containsExtent),
/* harmony export */   "containsXY": () => (/* binding */ containsXY),
/* harmony export */   "coordinateRelationship": () => (/* binding */ coordinateRelationship),
/* harmony export */   "createEmpty": () => (/* binding */ createEmpty),
/* harmony export */   "createOrUpdate": () => (/* binding */ createOrUpdate),
/* harmony export */   "createOrUpdateEmpty": () => (/* binding */ createOrUpdateEmpty),
/* harmony export */   "createOrUpdateFromCoordinate": () => (/* binding */ createOrUpdateFromCoordinate),
/* harmony export */   "createOrUpdateFromCoordinates": () => (/* binding */ createOrUpdateFromCoordinates),
/* harmony export */   "createOrUpdateFromFlatCoordinates": () => (/* binding */ createOrUpdateFromFlatCoordinates),
/* harmony export */   "createOrUpdateFromRings": () => (/* binding */ createOrUpdateFromRings),
/* harmony export */   "equals": () => (/* binding */ equals),
/* harmony export */   "extend": () => (/* binding */ extend),
/* harmony export */   "extendCoordinate": () => (/* binding */ extendCoordinate),
/* harmony export */   "extendCoordinates": () => (/* binding */ extendCoordinates),
/* harmony export */   "extendFlatCoordinates": () => (/* binding */ extendFlatCoordinates),
/* harmony export */   "extendRings": () => (/* binding */ extendRings),
/* harmony export */   "extendXY": () => (/* binding */ extendXY),
/* harmony export */   "forEachCorner": () => (/* binding */ forEachCorner),
/* harmony export */   "getArea": () => (/* binding */ getArea),
/* harmony export */   "getBottomLeft": () => (/* binding */ getBottomLeft),
/* harmony export */   "getBottomRight": () => (/* binding */ getBottomRight),
/* harmony export */   "getCenter": () => (/* binding */ getCenter),
/* harmony export */   "getCorner": () => (/* binding */ getCorner),
/* harmony export */   "getEnlargedArea": () => (/* binding */ getEnlargedArea),
/* harmony export */   "getForViewAndSize": () => (/* binding */ getForViewAndSize),
/* harmony export */   "getHeight": () => (/* binding */ getHeight),
/* harmony export */   "getIntersectionArea": () => (/* binding */ getIntersectionArea),
/* harmony export */   "getIntersection": () => (/* binding */ getIntersection),
/* harmony export */   "getMargin": () => (/* binding */ getMargin),
/* harmony export */   "getSize": () => (/* binding */ getSize),
/* harmony export */   "getTopLeft": () => (/* binding */ getTopLeft),
/* harmony export */   "getTopRight": () => (/* binding */ getTopRight),
/* harmony export */   "getWidth": () => (/* binding */ getWidth),
/* harmony export */   "intersects": () => (/* binding */ intersects),
/* harmony export */   "isEmpty": () => (/* binding */ isEmpty),
/* harmony export */   "returnOrUpdate": () => (/* binding */ returnOrUpdate),
/* harmony export */   "scaleFromCenter": () => (/* binding */ scaleFromCenter),
/* harmony export */   "intersectsSegment": () => (/* binding */ intersectsSegment),
/* harmony export */   "applyTransform": () => (/* binding */ applyTransform)
/* harmony export */ });
/* harmony import */ var _asserts_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./asserts.js */ "./node_modules/ol/asserts.js");
/* harmony import */ var _extent_Corner_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./extent/Corner.js */ "./node_modules/ol/extent/Corner.js");
/* harmony import */ var _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./extent/Relationship.js */ "./node_modules/ol/extent/Relationship.js");
/**
 * @module ol/extent
 */





/**
 * An array of numbers representing an extent: `[minx, miny, maxx, maxy]`.
 * @typedef {Array<number>} Extent
 * @api
 */

/**
 * Build an extent that includes all given coordinates.
 *
 * @param {Array<import("./coordinate.js").Coordinate>} coordinates Coordinates.
 * @return {Extent} Bounding extent.
 * @api
 */
function boundingExtent(coordinates) {
  var extent = createEmpty();
  for (var i = 0, ii = coordinates.length; i < ii; ++i) {
    extendCoordinate(extent, coordinates[i]);
  }
  return extent;
}


/**
 * @param {Array<number>} xs Xs.
 * @param {Array<number>} ys Ys.
 * @param {Extent=} opt_extent Destination extent.
 * @private
 * @return {Extent} Extent.
 */
function _boundingExtentXYs(xs, ys, opt_extent) {
  var minX = Math.min.apply(null, xs);
  var minY = Math.min.apply(null, ys);
  var maxX = Math.max.apply(null, xs);
  var maxY = Math.max.apply(null, ys);
  return createOrUpdate(minX, minY, maxX, maxY, opt_extent);
}


/**
 * Return extent increased by the provided value.
 * @param {Extent} extent Extent.
 * @param {number} value The amount by which the extent should be buffered.
 * @param {Extent=} opt_extent Extent.
 * @return {Extent} Extent.
 * @api
 */
function buffer(extent, value, opt_extent) {
  if (opt_extent) {
    opt_extent[0] = extent[0] - value;
    opt_extent[1] = extent[1] - value;
    opt_extent[2] = extent[2] + value;
    opt_extent[3] = extent[3] + value;
    return opt_extent;
  } else {
    return [
      extent[0] - value,
      extent[1] - value,
      extent[2] + value,
      extent[3] + value
    ];
  }
}


/**
 * Creates a clone of an extent.
 *
 * @param {Extent} extent Extent to clone.
 * @param {Extent=} opt_extent Extent.
 * @return {Extent} The clone.
 */
function clone(extent, opt_extent) {
  if (opt_extent) {
    opt_extent[0] = extent[0];
    opt_extent[1] = extent[1];
    opt_extent[2] = extent[2];
    opt_extent[3] = extent[3];
    return opt_extent;
  } else {
    return extent.slice();
  }
}


/**
 * @param {Extent} extent Extent.
 * @param {number} x X.
 * @param {number} y Y.
 * @return {number} Closest squared distance.
 */
function closestSquaredDistanceXY(extent, x, y) {
  var dx, dy;
  if (x < extent[0]) {
    dx = extent[0] - x;
  } else if (extent[2] < x) {
    dx = x - extent[2];
  } else {
    dx = 0;
  }
  if (y < extent[1]) {
    dy = extent[1] - y;
  } else if (extent[3] < y) {
    dy = y - extent[3];
  } else {
    dy = 0;
  }
  return dx * dx + dy * dy;
}


/**
 * Check if the passed coordinate is contained or on the edge of the extent.
 *
 * @param {Extent} extent Extent.
 * @param {import("./coordinate.js").Coordinate} coordinate Coordinate.
 * @return {boolean} The coordinate is contained in the extent.
 * @api
 */
function containsCoordinate(extent, coordinate) {
  return containsXY(extent, coordinate[0], coordinate[1]);
}


/**
 * Check if one extent contains another.
 *
 * An extent is deemed contained if it lies completely within the other extent,
 * including if they share one or more edges.
 *
 * @param {Extent} extent1 Extent 1.
 * @param {Extent} extent2 Extent 2.
 * @return {boolean} The second extent is contained by or on the edge of the
 *     first.
 * @api
 */
function containsExtent(extent1, extent2) {
  return extent1[0] <= extent2[0] && extent2[2] <= extent1[2] &&
      extent1[1] <= extent2[1] && extent2[3] <= extent1[3];
}


/**
 * Check if the passed coordinate is contained or on the edge of the extent.
 *
 * @param {Extent} extent Extent.
 * @param {number} x X coordinate.
 * @param {number} y Y coordinate.
 * @return {boolean} The x, y values are contained in the extent.
 * @api
 */
function containsXY(extent, x, y) {
  return extent[0] <= x && x <= extent[2] && extent[1] <= y && y <= extent[3];
}


/**
 * Get the relationship between a coordinate and extent.
 * @param {Extent} extent The extent.
 * @param {import("./coordinate.js").Coordinate} coordinate The coordinate.
 * @return {Relationship} The relationship (bitwise compare with
 *     import("./extent/Relationship.js").Relationship).
 */
function coordinateRelationship(extent, coordinate) {
  var minX = extent[0];
  var minY = extent[1];
  var maxX = extent[2];
  var maxY = extent[3];
  var x = coordinate[0];
  var y = coordinate[1];
  var relationship = _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].UNKNOWN;
  if (x < minX) {
    relationship = relationship | _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].LEFT;
  } else if (x > maxX) {
    relationship = relationship | _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].RIGHT;
  }
  if (y < minY) {
    relationship = relationship | _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].BELOW;
  } else if (y > maxY) {
    relationship = relationship | _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].ABOVE;
  }
  if (relationship === _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].UNKNOWN) {
    relationship = _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].INTERSECTING;
  }
  return relationship;
}


/**
 * Create an empty extent.
 * @return {Extent} Empty extent.
 * @api
 */
function createEmpty() {
  return [Infinity, Infinity, -Infinity, -Infinity];
}


/**
 * Create a new extent or update the provided extent.
 * @param {number} minX Minimum X.
 * @param {number} minY Minimum Y.
 * @param {number} maxX Maximum X.
 * @param {number} maxY Maximum Y.
 * @param {Extent=} opt_extent Destination extent.
 * @return {Extent} Extent.
 */
function createOrUpdate(minX, minY, maxX, maxY, opt_extent) {
  if (opt_extent) {
    opt_extent[0] = minX;
    opt_extent[1] = minY;
    opt_extent[2] = maxX;
    opt_extent[3] = maxY;
    return opt_extent;
  } else {
    return [minX, minY, maxX, maxY];
  }
}


/**
 * Create a new empty extent or make the provided one empty.
 * @param {Extent=} opt_extent Extent.
 * @return {Extent} Extent.
 */
function createOrUpdateEmpty(opt_extent) {
  return createOrUpdate(
    Infinity, Infinity, -Infinity, -Infinity, opt_extent);
}


/**
 * @param {import("./coordinate.js").Coordinate} coordinate Coordinate.
 * @param {Extent=} opt_extent Extent.
 * @return {Extent} Extent.
 */
function createOrUpdateFromCoordinate(coordinate, opt_extent) {
  var x = coordinate[0];
  var y = coordinate[1];
  return createOrUpdate(x, y, x, y, opt_extent);
}


/**
 * @param {Array<import("./coordinate.js").Coordinate>} coordinates Coordinates.
 * @param {Extent=} opt_extent Extent.
 * @return {Extent} Extent.
 */
function createOrUpdateFromCoordinates(coordinates, opt_extent) {
  var extent = createOrUpdateEmpty(opt_extent);
  return extendCoordinates(extent, coordinates);
}


/**
 * @param {Array<number>} flatCoordinates Flat coordinates.
 * @param {number} offset Offset.
 * @param {number} end End.
 * @param {number} stride Stride.
 * @param {Extent=} opt_extent Extent.
 * @return {Extent} Extent.
 */
function createOrUpdateFromFlatCoordinates(flatCoordinates, offset, end, stride, opt_extent) {
  var extent = createOrUpdateEmpty(opt_extent);
  return extendFlatCoordinates(extent, flatCoordinates, offset, end, stride);
}

/**
 * @param {Array<Array<import("./coordinate.js").Coordinate>>} rings Rings.
 * @param {Extent=} opt_extent Extent.
 * @return {Extent} Extent.
 */
function createOrUpdateFromRings(rings, opt_extent) {
  var extent = createOrUpdateEmpty(opt_extent);
  return extendRings(extent, rings);
}


/**
 * Determine if two extents are equivalent.
 * @param {Extent} extent1 Extent 1.
 * @param {Extent} extent2 Extent 2.
 * @return {boolean} The two extents are equivalent.
 * @api
 */
function equals(extent1, extent2) {
  return extent1[0] == extent2[0] && extent1[2] == extent2[2] &&
      extent1[1] == extent2[1] && extent1[3] == extent2[3];
}


/**
 * Modify an extent to include another extent.
 * @param {Extent} extent1 The extent to be modified.
 * @param {Extent} extent2 The extent that will be included in the first.
 * @return {Extent} A reference to the first (extended) extent.
 * @api
 */
function extend(extent1, extent2) {
  if (extent2[0] < extent1[0]) {
    extent1[0] = extent2[0];
  }
  if (extent2[2] > extent1[2]) {
    extent1[2] = extent2[2];
  }
  if (extent2[1] < extent1[1]) {
    extent1[1] = extent2[1];
  }
  if (extent2[3] > extent1[3]) {
    extent1[3] = extent2[3];
  }
  return extent1;
}


/**
 * @param {Extent} extent Extent.
 * @param {import("./coordinate.js").Coordinate} coordinate Coordinate.
 */
function extendCoordinate(extent, coordinate) {
  if (coordinate[0] < extent[0]) {
    extent[0] = coordinate[0];
  }
  if (coordinate[0] > extent[2]) {
    extent[2] = coordinate[0];
  }
  if (coordinate[1] < extent[1]) {
    extent[1] = coordinate[1];
  }
  if (coordinate[1] > extent[3]) {
    extent[3] = coordinate[1];
  }
}


/**
 * @param {Extent} extent Extent.
 * @param {Array<import("./coordinate.js").Coordinate>} coordinates Coordinates.
 * @return {Extent} Extent.
 */
function extendCoordinates(extent, coordinates) {
  for (var i = 0, ii = coordinates.length; i < ii; ++i) {
    extendCoordinate(extent, coordinates[i]);
  }
  return extent;
}


/**
 * @param {Extent} extent Extent.
 * @param {Array<number>} flatCoordinates Flat coordinates.
 * @param {number} offset Offset.
 * @param {number} end End.
 * @param {number} stride Stride.
 * @return {Extent} Extent.
 */
function extendFlatCoordinates(extent, flatCoordinates, offset, end, stride) {
  for (; offset < end; offset += stride) {
    extendXY(extent, flatCoordinates[offset], flatCoordinates[offset + 1]);
  }
  return extent;
}


/**
 * @param {Extent} extent Extent.
 * @param {Array<Array<import("./coordinate.js").Coordinate>>} rings Rings.
 * @return {Extent} Extent.
 */
function extendRings(extent, rings) {
  for (var i = 0, ii = rings.length; i < ii; ++i) {
    extendCoordinates(extent, rings[i]);
  }
  return extent;
}


/**
 * @param {Extent} extent Extent.
 * @param {number} x X.
 * @param {number} y Y.
 */
function extendXY(extent, x, y) {
  extent[0] = Math.min(extent[0], x);
  extent[1] = Math.min(extent[1], y);
  extent[2] = Math.max(extent[2], x);
  extent[3] = Math.max(extent[3], y);
}


/**
 * This function calls `callback` for each corner of the extent. If the
 * callback returns a truthy value the function returns that value
 * immediately. Otherwise the function returns `false`.
 * @param {Extent} extent Extent.
 * @param {function(this:T, import("./coordinate.js").Coordinate): S} callback Callback.
 * @param {T=} opt_this Value to use as `this` when executing `callback`.
 * @return {S|boolean} Value.
 * @template S, T
 */
function forEachCorner(extent, callback, opt_this) {
  var val;
  val = callback.call(opt_this, getBottomLeft(extent));
  if (val) {
    return val;
  }
  val = callback.call(opt_this, getBottomRight(extent));
  if (val) {
    return val;
  }
  val = callback.call(opt_this, getTopRight(extent));
  if (val) {
    return val;
  }
  val = callback.call(opt_this, getTopLeft(extent));
  if (val) {
    return val;
  }
  return false;
}


/**
 * Get the size of an extent.
 * @param {Extent} extent Extent.
 * @return {number} Area.
 * @api
 */
function getArea(extent) {
  var area = 0;
  if (!isEmpty(extent)) {
    area = getWidth(extent) * getHeight(extent);
  }
  return area;
}


/**
 * Get the bottom left coordinate of an extent.
 * @param {Extent} extent Extent.
 * @return {import("./coordinate.js").Coordinate} Bottom left coordinate.
 * @api
 */
function getBottomLeft(extent) {
  return [extent[0], extent[1]];
}


/**
 * Get the bottom right coordinate of an extent.
 * @param {Extent} extent Extent.
 * @return {import("./coordinate.js").Coordinate} Bottom right coordinate.
 * @api
 */
function getBottomRight(extent) {
  return [extent[2], extent[1]];
}


/**
 * Get the center coordinate of an extent.
 * @param {Extent} extent Extent.
 * @return {import("./coordinate.js").Coordinate} Center.
 * @api
 */
function getCenter(extent) {
  return [(extent[0] + extent[2]) / 2, (extent[1] + extent[3]) / 2];
}


/**
 * Get a corner coordinate of an extent.
 * @param {Extent} extent Extent.
 * @param {Corner} corner Corner.
 * @return {import("./coordinate.js").Coordinate} Corner coordinate.
 */
function getCorner(extent, corner) {
  var coordinate;
  if (corner === _extent_Corner_js__WEBPACK_IMPORTED_MODULE_1__["default"].BOTTOM_LEFT) {
    coordinate = getBottomLeft(extent);
  } else if (corner === _extent_Corner_js__WEBPACK_IMPORTED_MODULE_1__["default"].BOTTOM_RIGHT) {
    coordinate = getBottomRight(extent);
  } else if (corner === _extent_Corner_js__WEBPACK_IMPORTED_MODULE_1__["default"].TOP_LEFT) {
    coordinate = getTopLeft(extent);
  } else if (corner === _extent_Corner_js__WEBPACK_IMPORTED_MODULE_1__["default"].TOP_RIGHT) {
    coordinate = getTopRight(extent);
  } else {
    (0,_asserts_js__WEBPACK_IMPORTED_MODULE_2__.assert)(false, 13); // Invalid corner
  }
  return coordinate;
}


/**
 * @param {Extent} extent1 Extent 1.
 * @param {Extent} extent2 Extent 2.
 * @return {number} Enlarged area.
 */
function getEnlargedArea(extent1, extent2) {
  var minX = Math.min(extent1[0], extent2[0]);
  var minY = Math.min(extent1[1], extent2[1]);
  var maxX = Math.max(extent1[2], extent2[2]);
  var maxY = Math.max(extent1[3], extent2[3]);
  return (maxX - minX) * (maxY - minY);
}


/**
 * @param {import("./coordinate.js").Coordinate} center Center.
 * @param {number} resolution Resolution.
 * @param {number} rotation Rotation.
 * @param {import("./size.js").Size} size Size.
 * @param {Extent=} opt_extent Destination extent.
 * @return {Extent} Extent.
 */
function getForViewAndSize(center, resolution, rotation, size, opt_extent) {
  var dx = resolution * size[0] / 2;
  var dy = resolution * size[1] / 2;
  var cosRotation = Math.cos(rotation);
  var sinRotation = Math.sin(rotation);
  var xCos = dx * cosRotation;
  var xSin = dx * sinRotation;
  var yCos = dy * cosRotation;
  var ySin = dy * sinRotation;
  var x = center[0];
  var y = center[1];
  var x0 = x - xCos + ySin;
  var x1 = x - xCos - ySin;
  var x2 = x + xCos - ySin;
  var x3 = x + xCos + ySin;
  var y0 = y - xSin - yCos;
  var y1 = y - xSin + yCos;
  var y2 = y + xSin + yCos;
  var y3 = y + xSin - yCos;
  return createOrUpdate(
    Math.min(x0, x1, x2, x3), Math.min(y0, y1, y2, y3),
    Math.max(x0, x1, x2, x3), Math.max(y0, y1, y2, y3),
    opt_extent);
}


/**
 * Get the height of an extent.
 * @param {Extent} extent Extent.
 * @return {number} Height.
 * @api
 */
function getHeight(extent) {
  return extent[3] - extent[1];
}


/**
 * @param {Extent} extent1 Extent 1.
 * @param {Extent} extent2 Extent 2.
 * @return {number} Intersection area.
 */
function getIntersectionArea(extent1, extent2) {
  var intersection = getIntersection(extent1, extent2);
  return getArea(intersection);
}


/**
 * Get the intersection of two extents.
 * @param {Extent} extent1 Extent 1.
 * @param {Extent} extent2 Extent 2.
 * @param {Extent=} opt_extent Optional extent to populate with intersection.
 * @return {Extent} Intersecting extent.
 * @api
 */
function getIntersection(extent1, extent2, opt_extent) {
  var intersection = opt_extent ? opt_extent : createEmpty();
  if (intersects(extent1, extent2)) {
    if (extent1[0] > extent2[0]) {
      intersection[0] = extent1[0];
    } else {
      intersection[0] = extent2[0];
    }
    if (extent1[1] > extent2[1]) {
      intersection[1] = extent1[1];
    } else {
      intersection[1] = extent2[1];
    }
    if (extent1[2] < extent2[2]) {
      intersection[2] = extent1[2];
    } else {
      intersection[2] = extent2[2];
    }
    if (extent1[3] < extent2[3]) {
      intersection[3] = extent1[3];
    } else {
      intersection[3] = extent2[3];
    }
  } else {
    createOrUpdateEmpty(intersection);
  }
  return intersection;
}


/**
 * @param {Extent} extent Extent.
 * @return {number} Margin.
 */
function getMargin(extent) {
  return getWidth(extent) + getHeight(extent);
}


/**
 * Get the size (width, height) of an extent.
 * @param {Extent} extent The extent.
 * @return {import("./size.js").Size} The extent size.
 * @api
 */
function getSize(extent) {
  return [extent[2] - extent[0], extent[3] - extent[1]];
}


/**
 * Get the top left coordinate of an extent.
 * @param {Extent} extent Extent.
 * @return {import("./coordinate.js").Coordinate} Top left coordinate.
 * @api
 */
function getTopLeft(extent) {
  return [extent[0], extent[3]];
}


/**
 * Get the top right coordinate of an extent.
 * @param {Extent} extent Extent.
 * @return {import("./coordinate.js").Coordinate} Top right coordinate.
 * @api
 */
function getTopRight(extent) {
  return [extent[2], extent[3]];
}


/**
 * Get the width of an extent.
 * @param {Extent} extent Extent.
 * @return {number} Width.
 * @api
 */
function getWidth(extent) {
  return extent[2] - extent[0];
}


/**
 * Determine if one extent intersects another.
 * @param {Extent} extent1 Extent 1.
 * @param {Extent} extent2 Extent.
 * @return {boolean} The two extents intersect.
 * @api
 */
function intersects(extent1, extent2) {
  return extent1[0] <= extent2[2] &&
      extent1[2] >= extent2[0] &&
      extent1[1] <= extent2[3] &&
      extent1[3] >= extent2[1];
}


/**
 * Determine if an extent is empty.
 * @param {Extent} extent Extent.
 * @return {boolean} Is empty.
 * @api
 */
function isEmpty(extent) {
  return extent[2] < extent[0] || extent[3] < extent[1];
}


/**
 * @param {Extent} extent Extent.
 * @param {Extent=} opt_extent Extent.
 * @return {Extent} Extent.
 */
function returnOrUpdate(extent, opt_extent) {
  if (opt_extent) {
    opt_extent[0] = extent[0];
    opt_extent[1] = extent[1];
    opt_extent[2] = extent[2];
    opt_extent[3] = extent[3];
    return opt_extent;
  } else {
    return extent;
  }
}


/**
 * @param {Extent} extent Extent.
 * @param {number} value Value.
 */
function scaleFromCenter(extent, value) {
  var deltaX = ((extent[2] - extent[0]) / 2) * (value - 1);
  var deltaY = ((extent[3] - extent[1]) / 2) * (value - 1);
  extent[0] -= deltaX;
  extent[2] += deltaX;
  extent[1] -= deltaY;
  extent[3] += deltaY;
}


/**
 * Determine if the segment between two coordinates intersects (crosses,
 * touches, or is contained by) the provided extent.
 * @param {Extent} extent The extent.
 * @param {import("./coordinate.js").Coordinate} start Segment start coordinate.
 * @param {import("./coordinate.js").Coordinate} end Segment end coordinate.
 * @return {boolean} The segment intersects the extent.
 */
function intersectsSegment(extent, start, end) {
  var intersects = false;
  var startRel = coordinateRelationship(extent, start);
  var endRel = coordinateRelationship(extent, end);
  if (startRel === _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].INTERSECTING ||
      endRel === _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].INTERSECTING) {
    intersects = true;
  } else {
    var minX = extent[0];
    var minY = extent[1];
    var maxX = extent[2];
    var maxY = extent[3];
    var startX = start[0];
    var startY = start[1];
    var endX = end[0];
    var endY = end[1];
    var slope = (endY - startY) / (endX - startX);
    var x, y;
    if (!!(endRel & _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].ABOVE) &&
        !(startRel & _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].ABOVE)) {
      // potentially intersects top
      x = endX - ((endY - maxY) / slope);
      intersects = x >= minX && x <= maxX;
    }
    if (!intersects && !!(endRel & _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].RIGHT) &&
        !(startRel & _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].RIGHT)) {
      // potentially intersects right
      y = endY - ((endX - maxX) * slope);
      intersects = y >= minY && y <= maxY;
    }
    if (!intersects && !!(endRel & _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].BELOW) &&
        !(startRel & _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].BELOW)) {
      // potentially intersects bottom
      x = endX - ((endY - minY) / slope);
      intersects = x >= minX && x <= maxX;
    }
    if (!intersects && !!(endRel & _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].LEFT) &&
        !(startRel & _extent_Relationship_js__WEBPACK_IMPORTED_MODULE_0__["default"].LEFT)) {
      // potentially intersects left
      y = endY - ((endX - minX) * slope);
      intersects = y >= minY && y <= maxY;
    }

  }
  return intersects;
}


/**
 * Apply a transform function to the extent.
 * @param {Extent} extent Extent.
 * @param {import("./proj.js").TransformFunction} transformFn Transform function.
 * Called with `[minX, minY, maxX, maxY]` extent coordinates.
 * @param {Extent=} opt_extent Destination extent.
 * @return {Extent} Extent.
 * @api
 */
function applyTransform(extent, transformFn, opt_extent) {
  var coordinates = [
    extent[0], extent[1],
    extent[0], extent[3],
    extent[2], extent[1],
    extent[2], extent[3]
  ];
  transformFn(coordinates, coordinates, 2);
  var xs = [coordinates[0], coordinates[2], coordinates[4], coordinates[6]];
  var ys = [coordinates[1], coordinates[3], coordinates[5], coordinates[7]];
  return _boundingExtentXYs(xs, ys, opt_extent);
}

//# sourceMappingURL=extent.js.map

/***/ }),

/***/ "./node_modules/ol/extent/Corner.js":
/*!******************************************!*\
  !*** ./node_modules/ol/extent/Corner.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/extent/Corner
 */

/**
 * Extent corner.
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  BOTTOM_LEFT: 'bottom-left',
  BOTTOM_RIGHT: 'bottom-right',
  TOP_LEFT: 'top-left',
  TOP_RIGHT: 'top-right'
});

//# sourceMappingURL=Corner.js.map

/***/ }),

/***/ "./node_modules/ol/extent/Relationship.js":
/*!************************************************!*\
  !*** ./node_modules/ol/extent/Relationship.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/extent/Relationship
 */

/**
 * Relationship to an extent.
 * @enum {number}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  UNKNOWN: 0,
  INTERSECTING: 1,
  ABOVE: 2,
  RIGHT: 4,
  BELOW: 8,
  LEFT: 16
});

//# sourceMappingURL=Relationship.js.map

/***/ }),

/***/ "./node_modules/ol/featureloader.js":
/*!******************************************!*\
  !*** ./node_modules/ol/featureloader.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "loadFeaturesXhr": () => (/* binding */ loadFeaturesXhr),
/* harmony export */   "xhr": () => (/* binding */ xhr)
/* harmony export */ });
/* harmony import */ var _functions_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./functions.js */ "./node_modules/ol/functions.js");
/* harmony import */ var _format_FormatType_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./format/FormatType.js */ "./node_modules/ol/format/FormatType.js");
/**
 * @module ol/featureloader
 */



/**
 * {@link module:ol/source/Vector} sources use a function of this type to
 * load features.
 *
 * This function takes an {@link module:ol/extent~Extent} representing the area to be loaded,
 * a `{number}` representing the resolution (map units per pixel) and an
 * {@link module:ol/proj/Projection} for the projection  as
 * arguments. `this` within the function is bound to the
 * {@link module:ol/source/Vector} it's called from.
 *
 * The function is responsible for loading the features and adding them to the
 * source.
 * @typedef {function(this:(import("./source/Vector").default|import("./VectorTile.js").default), import("./extent.js").Extent, number,
 *                    import("./proj/Projection.js").default)} FeatureLoader
 * @api
 */


/**
 * {@link module:ol/source/Vector} sources use a function of this type to
 * get the url to load features from.
 *
 * This function takes an {@link module:ol/extent~Extent} representing the area
 * to be loaded, a `{number}` representing the resolution (map units per pixel)
 * and an {@link module:ol/proj/Projection} for the projection  as
 * arguments and returns a `{string}` representing the URL.
 * @typedef {function(import("./extent.js").Extent, number, import("./proj/Projection.js").default): string} FeatureUrlFunction
 * @api
 */


/**
 * @param {string|FeatureUrlFunction} url Feature URL service.
 * @param {import("./format/Feature.js").default} format Feature format.
 * @param {function(this:import("./VectorTile.js").default, Array<import("./Feature.js").default>, import("./proj/Projection.js").default, import("./extent.js").Extent)|function(this:import("./source/Vector").default, Array<import("./Feature.js").default>)} success
 *     Function called with the loaded features and optionally with the data
 *     projection. Called with the vector tile or source as `this`.
 * @param {function(this:import("./VectorTile.js").default)|function(this:import("./source/Vector").default)} failure
 *     Function called when loading failed. Called with the vector tile or
 *     source as `this`.
 * @return {FeatureLoader} The feature loader.
 */
function loadFeaturesXhr(url, format, success, failure) {
  return (
    /**
     * @param {import("./extent.js").Extent} extent Extent.
     * @param {number} resolution Resolution.
     * @param {import("./proj/Projection.js").default} projection Projection.
     * @this {import("./source/Vector").default|import("./VectorTile.js").default}
     */
    function(extent, resolution, projection) {
      var xhr = new XMLHttpRequest();
      xhr.open('GET',
        typeof url === 'function' ? url(extent, resolution, projection) : url,
        true);
      if (format.getType() == _format_FormatType_js__WEBPACK_IMPORTED_MODULE_0__["default"].ARRAY_BUFFER) {
        xhr.responseType = 'arraybuffer';
      }
      /**
       * @param {Event} event Event.
       * @private
       */
      xhr.onload = function(event) {
        // status will be 0 for file:// urls
        if (!xhr.status || xhr.status >= 200 && xhr.status < 300) {
          var type = format.getType();
          /** @type {Document|Node|Object|string|undefined} */
          var source;
          if (type == _format_FormatType_js__WEBPACK_IMPORTED_MODULE_0__["default"].JSON || type == _format_FormatType_js__WEBPACK_IMPORTED_MODULE_0__["default"].TEXT) {
            source = xhr.responseText;
          } else if (type == _format_FormatType_js__WEBPACK_IMPORTED_MODULE_0__["default"].XML) {
            source = xhr.responseXML;
            if (!source) {
              source = new DOMParser().parseFromString(xhr.responseText, 'application/xml');
            }
          } else if (type == _format_FormatType_js__WEBPACK_IMPORTED_MODULE_0__["default"].ARRAY_BUFFER) {
            source = /** @type {ArrayBuffer} */ (xhr.response);
          }
          if (source) {
            success.call(this, format.readFeatures(source,
              {featureProjection: projection}),
            format.readProjection(source), format.getLastExtent());
          } else {
            failure.call(this);
          }
        } else {
          failure.call(this);
        }
      }.bind(this);
      /**
       * @private
       */
      xhr.onerror = function() {
        failure.call(this);
      }.bind(this);
      xhr.send();
    }
  );
}


/**
 * Create an XHR feature loader for a `url` and `format`. The feature loader
 * loads features (with XHR), parses the features, and adds them to the
 * vector source.
 * @param {string|FeatureUrlFunction} url Feature URL service.
 * @param {import("./format/Feature.js").default} format Feature format.
 * @return {FeatureLoader} The feature loader.
 * @api
 */
function xhr(url, format) {
  return loadFeaturesXhr(url, format,
    /**
     * @param {Array<import("./Feature.js").default>} features The loaded features.
     * @param {import("./proj/Projection.js").default} dataProjection Data
     * projection.
     * @this {import("./source/Vector").default|import("./VectorTile.js").default}
     */
    function(features, dataProjection) {
      var sourceOrTile = /** @type {?} */ (this);
      if (typeof sourceOrTile.addFeatures === 'function') {
        /** @type {import("./source/Vector").default} */ (sourceOrTile).addFeatures(features);
      }
    }, /* FIXME handle error */ _functions_js__WEBPACK_IMPORTED_MODULE_1__.VOID);
}

//# sourceMappingURL=featureloader.js.map

/***/ }),

/***/ "./node_modules/ol/format/FormatType.js":
/*!**********************************************!*\
  !*** ./node_modules/ol/format/FormatType.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/format/FormatType
 */

/**
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  ARRAY_BUFFER: 'arraybuffer',
  JSON: 'json',
  TEXT: 'text',
  XML: 'xml'
});

//# sourceMappingURL=FormatType.js.map

/***/ }),

/***/ "./node_modules/ol/functions.js":
/*!**************************************!*\
  !*** ./node_modules/ol/functions.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "TRUE": () => (/* binding */ TRUE),
/* harmony export */   "FALSE": () => (/* binding */ FALSE),
/* harmony export */   "VOID": () => (/* binding */ VOID)
/* harmony export */ });
/**
 * @module ol/functions
 */

/**
 * Always returns true.
 * @returns {boolean} true.
 */
function TRUE() {
  return true;
}

/**
 * Always returns false.
 * @returns {boolean} false.
 */
function FALSE() {
  return false;
}

/**
 * A reusable function, used e.g. as a default for callbacks.
 *
 * @return {void} Nothing.
 */
function VOID() {}

//# sourceMappingURL=functions.js.map

/***/ }),

/***/ "./node_modules/ol/geom/GeometryType.js":
/*!**********************************************!*\
  !*** ./node_modules/ol/geom/GeometryType.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/geom/GeometryType
 */

/**
 * The geometry type. One of `'Point'`, `'LineString'`, `'LinearRing'`,
 * `'Polygon'`, `'MultiPoint'`, `'MultiLineString'`, `'MultiPolygon'`,
 * `'GeometryCollection'`, `'Circle'`.
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  POINT: 'Point',
  LINE_STRING: 'LineString',
  LINEAR_RING: 'LinearRing',
  POLYGON: 'Polygon',
  MULTI_POINT: 'MultiPoint',
  MULTI_LINE_STRING: 'MultiLineString',
  MULTI_POLYGON: 'MultiPolygon',
  GEOMETRY_COLLECTION: 'GeometryCollection',
  CIRCLE: 'Circle'
});

//# sourceMappingURL=GeometryType.js.map

/***/ }),

/***/ "./node_modules/ol/has.js":
/*!********************************!*\
  !*** ./node_modules/ol/has.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "FIREFOX": () => (/* binding */ FIREFOX),
/* harmony export */   "SAFARI": () => (/* binding */ SAFARI),
/* harmony export */   "WEBKIT": () => (/* binding */ WEBKIT),
/* harmony export */   "MAC": () => (/* binding */ MAC),
/* harmony export */   "DEVICE_PIXEL_RATIO": () => (/* binding */ DEVICE_PIXEL_RATIO),
/* harmony export */   "CANVAS_LINE_DASH": () => (/* binding */ CANVAS_LINE_DASH),
/* harmony export */   "GEOLOCATION": () => (/* binding */ GEOLOCATION),
/* harmony export */   "TOUCH": () => (/* binding */ TOUCH),
/* harmony export */   "POINTER": () => (/* binding */ POINTER),
/* harmony export */   "MSPOINTER": () => (/* binding */ MSPOINTER),
/* harmony export */   "WEBGL": () => (/* reexport safe */ _webgl_js__WEBPACK_IMPORTED_MODULE_0__.HAS)
/* harmony export */ });
/* harmony import */ var _webgl_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./webgl.js */ "./node_modules/ol/webgl.js");
/**
 * @module ol/has
 */

var ua = typeof navigator !== 'undefined' ?
  navigator.userAgent.toLowerCase() : '';

/**
 * User agent string says we are dealing with Firefox as browser.
 * @type {boolean}
 */
var FIREFOX = ua.indexOf('firefox') !== -1;

/**
 * User agent string says we are dealing with Safari as browser.
 * @type {boolean}
 */
var SAFARI = ua.indexOf('safari') !== -1 && ua.indexOf('chrom') == -1;

/**
 * User agent string says we are dealing with a WebKit engine.
 * @type {boolean}
 */
var WEBKIT = ua.indexOf('webkit') !== -1 && ua.indexOf('edge') == -1;

/**
 * User agent string says we are dealing with a Mac as platform.
 * @type {boolean}
 */
var MAC = ua.indexOf('macintosh') !== -1;


/**
 * The ratio between physical pixels and device-independent pixels
 * (dips) on the device (`window.devicePixelRatio`).
 * @const
 * @type {number}
 * @api
 */
var DEVICE_PIXEL_RATIO = window.devicePixelRatio || 1;


/**
 * True if the browser's Canvas implementation implements {get,set}LineDash.
 * @type {boolean}
 */
var CANVAS_LINE_DASH = function() {
  var has = false;
  try {
    has = !!document.createElement('canvas').getContext('2d').setLineDash;
  } catch (e) {
    // pass
  }
  return has;
}();


/**
 * Is HTML5 geolocation supported in the current browser?
 * @const
 * @type {boolean}
 * @api
 */
var GEOLOCATION = 'geolocation' in navigator;


/**
 * True if browser supports touch events.
 * @const
 * @type {boolean}
 * @api
 */
var TOUCH = 'ontouchstart' in window;


/**
 * True if browser supports pointer events.
 * @const
 * @type {boolean}
 */
var POINTER = 'PointerEvent' in window;


/**
 * True if browser supports ms pointer events (IE 10).
 * @const
 * @type {boolean}
 */
var MSPOINTER = !!(navigator.msPointerEnabled);




//# sourceMappingURL=has.js.map

/***/ }),

/***/ "./node_modules/ol/layer/Base.js":
/*!***************************************!*\
  !*** ./node_modules/ol/layer/Base.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../util.js */ "./node_modules/ol/util.js");
/* harmony import */ var _Object_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Object.js */ "./node_modules/ol/Object.js");
/* harmony import */ var _Property_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Property.js */ "./node_modules/ol/layer/Property.js");
/* harmony import */ var _math_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../math.js */ "./node_modules/ol/math.js");
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../obj.js */ "./node_modules/ol/obj.js");
/**
 * @module ol/layer/Base
 */







/**
 * @typedef {Object} Options
 * @property {number} [opacity=1] Opacity (0, 1).
 * @property {boolean} [visible=true] Visibility.
 * @property {import("../extent.js").Extent} [extent] The bounding extent for layer rendering.  The layer will not be
 * rendered outside of this extent.
 * @property {number} [zIndex] The z-index for layer rendering.  At rendering time, the layers
 * will be ordered, first by Z-index and then by position. When `undefined`, a `zIndex` of 0 is assumed
 * for layers that are added to the map's `layers` collection, or `Infinity` when the layer's `setMap()`
 * method was used.
 * @property {number} [minResolution] The minimum resolution (inclusive) at which this layer will be
 * visible.
 * @property {number} [maxResolution] The maximum resolution (exclusive) below which this layer will
 * be visible.
 */


/**
 * @classdesc
 * Abstract base class; normally only used for creating subclasses and not
 * instantiated in apps.
 * Note that with {@link module:ol/layer/Base} and all its subclasses, any property set in
 * the options is set as a {@link module:ol/Object} property on the layer object, so
 * is observable, and has get/set accessors.
 *
 * @api
 */
var BaseLayer = /*@__PURE__*/(function (BaseObject) {
  function BaseLayer(options) {

    BaseObject.call(this);

    /**
     * @type {Object<string, *>}
     */
    var properties = (0,_obj_js__WEBPACK_IMPORTED_MODULE_0__.assign)({}, options);
    properties[_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].OPACITY] =
       options.opacity !== undefined ? options.opacity : 1;
    properties[_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].VISIBLE] =
       options.visible !== undefined ? options.visible : true;
    properties[_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].Z_INDEX] = options.zIndex;
    properties[_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].MAX_RESOLUTION] =
       options.maxResolution !== undefined ? options.maxResolution : Infinity;
    properties[_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].MIN_RESOLUTION] =
       options.minResolution !== undefined ? options.minResolution : 0;

    this.setProperties(properties);

    /**
     * @type {import("./Layer.js").State}
     * @private
     */
    this.state_ = null;

    /**
     * The layer type.
     * @type {import("../LayerType.js").default}
     * @protected;
     */
    this.type;

  }

  if ( BaseObject ) BaseLayer.__proto__ = BaseObject;
  BaseLayer.prototype = Object.create( BaseObject && BaseObject.prototype );
  BaseLayer.prototype.constructor = BaseLayer;

  /**
   * Get the layer type (used when creating a layer renderer).
   * @return {import("../LayerType.js").default} The layer type.
   */
  BaseLayer.prototype.getType = function getType () {
    return this.type;
  };

  /**
   * @return {import("./Layer.js").State} Layer state.
   */
  BaseLayer.prototype.getLayerState = function getLayerState () {
    /** @type {import("./Layer.js").State} */
    var state = this.state_ || /** @type {?} */ ({
      layer: this,
      managed: true
    });
    state.opacity = (0,_math_js__WEBPACK_IMPORTED_MODULE_2__.clamp)(this.getOpacity(), 0, 1);
    state.sourceState = this.getSourceState();
    state.visible = this.getVisible();
    state.extent = this.getExtent();
    state.zIndex = this.getZIndex() || 0;
    state.maxResolution = this.getMaxResolution();
    state.minResolution = Math.max(this.getMinResolution(), 0);
    this.state_ = state;

    return state;
  };

  /**
   * @abstract
   * @param {Array<import("./Layer.js").default>=} opt_array Array of layers (to be
   *     modified in place).
   * @return {Array<import("./Layer.js").default>} Array of layers.
   */
  BaseLayer.prototype.getLayersArray = function getLayersArray (opt_array) {
    return (0,_util_js__WEBPACK_IMPORTED_MODULE_3__.abstract)();
  };

  /**
   * @abstract
   * @param {Array<import("./Layer.js").State>=} opt_states Optional list of layer
   *     states (to be modified in place).
   * @return {Array<import("./Layer.js").State>} List of layer states.
   */
  BaseLayer.prototype.getLayerStatesArray = function getLayerStatesArray (opt_states) {
    return (0,_util_js__WEBPACK_IMPORTED_MODULE_3__.abstract)();
  };

  /**
   * Return the {@link module:ol/extent~Extent extent} of the layer or `undefined` if it
   * will be visible regardless of extent.
   * @return {import("../extent.js").Extent|undefined} The layer extent.
   * @observable
   * @api
   */
  BaseLayer.prototype.getExtent = function getExtent () {
    return (
      /** @type {import("../extent.js").Extent|undefined} */ (this.get(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].EXTENT))
    );
  };

  /**
   * Return the maximum resolution of the layer.
   * @return {number} The maximum resolution of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.getMaxResolution = function getMaxResolution () {
    return /** @type {number} */ (this.get(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].MAX_RESOLUTION));
  };

  /**
   * Return the minimum resolution of the layer.
   * @return {number} The minimum resolution of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.getMinResolution = function getMinResolution () {
    return /** @type {number} */ (this.get(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].MIN_RESOLUTION));
  };

  /**
   * Return the opacity of the layer (between 0 and 1).
   * @return {number} The opacity of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.getOpacity = function getOpacity () {
    return /** @type {number} */ (this.get(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].OPACITY));
  };

  /**
   * @abstract
   * @return {import("../source/State.js").default} Source state.
   */
  BaseLayer.prototype.getSourceState = function getSourceState () {
    return (0,_util_js__WEBPACK_IMPORTED_MODULE_3__.abstract)();
  };

  /**
   * Return the visibility of the layer (`true` or `false`).
   * @return {boolean} The visibility of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.getVisible = function getVisible () {
    return /** @type {boolean} */ (this.get(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].VISIBLE));
  };

  /**
   * Return the Z-index of the layer, which is used to order layers before
   * rendering. The default Z-index is 0.
   * @return {number} The Z-index of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.getZIndex = function getZIndex () {
    return /** @type {number} */ (this.get(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].Z_INDEX));
  };

  /**
   * Set the extent at which the layer is visible.  If `undefined`, the layer
   * will be visible at all extents.
   * @param {import("../extent.js").Extent|undefined} extent The extent of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.setExtent = function setExtent (extent) {
    this.set(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].EXTENT, extent);
  };

  /**
   * Set the maximum resolution at which the layer is visible.
   * @param {number} maxResolution The maximum resolution of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.setMaxResolution = function setMaxResolution (maxResolution) {
    this.set(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].MAX_RESOLUTION, maxResolution);
  };

  /**
   * Set the minimum resolution at which the layer is visible.
   * @param {number} minResolution The minimum resolution of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.setMinResolution = function setMinResolution (minResolution) {
    this.set(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].MIN_RESOLUTION, minResolution);
  };

  /**
   * Set the opacity of the layer, allowed values range from 0 to 1.
   * @param {number} opacity The opacity of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.setOpacity = function setOpacity (opacity) {
    this.set(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].OPACITY, opacity);
  };

  /**
   * Set the visibility of the layer (`true` or `false`).
   * @param {boolean} visible The visibility of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.setVisible = function setVisible (visible) {
    this.set(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].VISIBLE, visible);
  };

  /**
   * Set Z-index of the layer, which is used to order layers before rendering.
   * The default Z-index is 0.
   * @param {number} zindex The z-index of the layer.
   * @observable
   * @api
   */
  BaseLayer.prototype.setZIndex = function setZIndex (zindex) {
    this.set(_Property_js__WEBPACK_IMPORTED_MODULE_1__["default"].Z_INDEX, zindex);
  };

  return BaseLayer;
}(_Object_js__WEBPACK_IMPORTED_MODULE_4__["default"]));


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BaseLayer);

//# sourceMappingURL=Base.js.map

/***/ }),

/***/ "./node_modules/ol/layer/Layer.js":
/*!****************************************!*\
  !*** ./node_modules/ol/layer/Layer.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "visibleAtResolution": () => (/* binding */ visibleAtResolution),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _events_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../events.js */ "./node_modules/ol/events.js");
/* harmony import */ var _events_EventType_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../events/EventType.js */ "./node_modules/ol/events/EventType.js");
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../util.js */ "./node_modules/ol/util.js");
/* harmony import */ var _Object_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Object.js */ "./node_modules/ol/Object.js");
/* harmony import */ var _Base_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./Base.js */ "./node_modules/ol/layer/Base.js");
/* harmony import */ var _Property_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Property.js */ "./node_modules/ol/layer/Property.js");
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../obj.js */ "./node_modules/ol/obj.js");
/* harmony import */ var _render_EventType_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../render/EventType.js */ "./node_modules/ol/render/EventType.js");
/* harmony import */ var _source_State_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../source/State.js */ "./node_modules/ol/source/State.js");
/**
 * @module ol/layer/Layer
 */











/**
 * @typedef {Object} Options
 * @property {number} [opacity=1] Opacity (0, 1).
 * @property {boolean} [visible=true] Visibility.
 * @property {import("../extent.js").Extent} [extent] The bounding extent for layer rendering.  The layer will not be
 * rendered outside of this extent.
 * @property {number} [zIndex] The z-index for layer rendering.  At rendering time, the layers
 * will be ordered, first by Z-index and then by position. When `undefined`, a `zIndex` of 0 is assumed
 * for layers that are added to the map's `layers` collection, or `Infinity` when the layer's `setMap()`
 * method was used.
 * @property {number} [minResolution] The minimum resolution (inclusive) at which this layer will be
 * visible.
 * @property {number} [maxResolution] The maximum resolution (exclusive) below which this layer will
 * be visible.
 * @property {import("../source/Source.js").default} [source] Source for this layer.  If not provided to the constructor,
 * the source can be set by calling {@link module:ol/layer/Layer#setSource layer.setSource(source)} after
 * construction.
 * @property {import("../PluggableMap.js").default} [map] Map.
 */


/**
 * @typedef {Object} State
 * @property {import("./Base.js").default} layer
 * @property {number} opacity
 * @property {SourceState} sourceState
 * @property {boolean} visible
 * @property {boolean} managed
 * @property {import("../extent.js").Extent} [extent]
 * @property {number} zIndex
 * @property {number} maxResolution
 * @property {number} minResolution
 */

/**
 * @classdesc
 * Abstract base class; normally only used for creating subclasses and not
 * instantiated in apps.
 * A visual representation of raster or vector map data.
 * Layers group together those properties that pertain to how the data is to be
 * displayed, irrespective of the source of that data.
 *
 * Layers are usually added to a map with {@link module:ol/Map#addLayer}. Components
 * like {@link module:ol/interaction/Select~Select} use unmanaged layers
 * internally. These unmanaged layers are associated with the map using
 * {@link module:ol/layer/Layer~Layer#setMap} instead.
 *
 * A generic `change` event is fired when the state of the source changes.
 *
 * @fires import("../render/Event.js").RenderEvent
 */
var Layer = /*@__PURE__*/(function (BaseLayer) {
  function Layer(options) {

    var baseOptions = (0,_obj_js__WEBPACK_IMPORTED_MODULE_0__.assign)({}, options);
    delete baseOptions.source;

    BaseLayer.call(this, baseOptions);

    /**
     * @private
     * @type {?import("../events.js").EventsKey}
     */
    this.mapPrecomposeKey_ = null;

    /**
     * @private
     * @type {?import("../events.js").EventsKey}
     */
    this.mapRenderKey_ = null;

    /**
     * @private
     * @type {?import("../events.js").EventsKey}
     */
    this.sourceChangeKey_ = null;

    if (options.map) {
      this.setMap(options.map);
    }

    (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.listen)(this,
      (0,_Object_js__WEBPACK_IMPORTED_MODULE_2__.getChangeEventType)(_Property_js__WEBPACK_IMPORTED_MODULE_3__["default"].SOURCE),
      this.handleSourcePropertyChange_, this);

    var source = options.source ? options.source : null;
    this.setSource(source);
  }

  if ( BaseLayer ) Layer.__proto__ = BaseLayer;
  Layer.prototype = Object.create( BaseLayer && BaseLayer.prototype );
  Layer.prototype.constructor = Layer;

  /**
   * @inheritDoc
   */
  Layer.prototype.getLayersArray = function getLayersArray (opt_array) {
    var array = opt_array ? opt_array : [];
    array.push(this);
    return array;
  };

  /**
   * @inheritDoc
   */
  Layer.prototype.getLayerStatesArray = function getLayerStatesArray (opt_states) {
    var states = opt_states ? opt_states : [];
    states.push(this.getLayerState());
    return states;
  };

  /**
   * Get the layer source.
   * @return {import("../source/Source.js").default} The layer source (or `null` if not yet set).
   * @observable
   * @api
   */
  Layer.prototype.getSource = function getSource () {
    var source = this.get(_Property_js__WEBPACK_IMPORTED_MODULE_3__["default"].SOURCE);
    return (
      /** @type {import("../source/Source.js").default} */ (source) || null
    );
  };

  /**
    * @inheritDoc
    */
  Layer.prototype.getSourceState = function getSourceState () {
    var source = this.getSource();
    return !source ? _source_State_js__WEBPACK_IMPORTED_MODULE_4__["default"].UNDEFINED : source.getState();
  };

  /**
   * @private
   */
  Layer.prototype.handleSourceChange_ = function handleSourceChange_ () {
    this.changed();
  };

  /**
   * @private
   */
  Layer.prototype.handleSourcePropertyChange_ = function handleSourcePropertyChange_ () {
    if (this.sourceChangeKey_) {
      (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.unlistenByKey)(this.sourceChangeKey_);
      this.sourceChangeKey_ = null;
    }
    var source = this.getSource();
    if (source) {
      this.sourceChangeKey_ = (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.listen)(source,
        _events_EventType_js__WEBPACK_IMPORTED_MODULE_5__["default"].CHANGE, this.handleSourceChange_, this);
    }
    this.changed();
  };

  /**
   * Sets the layer to be rendered on top of other layers on a map. The map will
   * not manage this layer in its layers collection, and the callback in
   * {@link module:ol/Map#forEachLayerAtPixel} will receive `null` as layer. This
   * is useful for temporary layers. To remove an unmanaged layer from the map,
   * use `#setMap(null)`.
   *
   * To add the layer to a map and have it managed by the map, use
   * {@link module:ol/Map#addLayer} instead.
   * @param {import("../PluggableMap.js").default} map Map.
   * @api
   */
  Layer.prototype.setMap = function setMap (map) {
    if (this.mapPrecomposeKey_) {
      (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.unlistenByKey)(this.mapPrecomposeKey_);
      this.mapPrecomposeKey_ = null;
    }
    if (!map) {
      this.changed();
    }
    if (this.mapRenderKey_) {
      (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.unlistenByKey)(this.mapRenderKey_);
      this.mapRenderKey_ = null;
    }
    if (map) {
      this.mapPrecomposeKey_ = (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.listen)(map, _render_EventType_js__WEBPACK_IMPORTED_MODULE_6__["default"].PRECOMPOSE, function(evt) {
        var renderEvent = /** @type {import("../render/Event.js").default} */ (evt);
        var layerState = this.getLayerState();
        layerState.managed = false;
        if (this.getZIndex() === undefined) {
          layerState.zIndex = Infinity;
        }
        renderEvent.frameState.layerStatesArray.push(layerState);
        renderEvent.frameState.layerStates[(0,_util_js__WEBPACK_IMPORTED_MODULE_7__.getUid)(this)] = layerState;
      }, this);
      this.mapRenderKey_ = (0,_events_js__WEBPACK_IMPORTED_MODULE_1__.listen)(this, _events_EventType_js__WEBPACK_IMPORTED_MODULE_5__["default"].CHANGE, map.render, map);
      this.changed();
    }
  };

  /**
   * Set the layer source.
   * @param {import("../source/Source.js").default} source The layer source.
   * @observable
   * @api
   */
  Layer.prototype.setSource = function setSource (source) {
    this.set(_Property_js__WEBPACK_IMPORTED_MODULE_3__["default"].SOURCE, source);
  };

  return Layer;
}(_Base_js__WEBPACK_IMPORTED_MODULE_8__["default"]));


/**
 * Return `true` if the layer is visible, and if the passed resolution is
 * between the layer's minResolution and maxResolution. The comparison is
 * inclusive for `minResolution` and exclusive for `maxResolution`.
 * @param {State} layerState Layer state.
 * @param {number} resolution Resolution.
 * @return {boolean} The layer is visible at the given resolution.
 */
function visibleAtResolution(layerState, resolution) {
  return layerState.visible && resolution >= layerState.minResolution &&
      resolution < layerState.maxResolution;
}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Layer);

//# sourceMappingURL=Layer.js.map

/***/ }),

/***/ "./node_modules/ol/layer/Property.js":
/*!*******************************************!*\
  !*** ./node_modules/ol/layer/Property.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/layer/Property
 */

/**
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  OPACITY: 'opacity',
  VISIBLE: 'visible',
  EXTENT: 'extent',
  Z_INDEX: 'zIndex',
  MAX_RESOLUTION: 'maxResolution',
  MIN_RESOLUTION: 'minResolution',
  SOURCE: 'source'
});

//# sourceMappingURL=Property.js.map

/***/ }),

/***/ "./node_modules/ol/layer/Vector.js":
/*!*****************************************!*\
  !*** ./node_modules/ol/layer/Vector.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LayerType_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../LayerType.js */ "./node_modules/ol/LayerType.js");
/* harmony import */ var _Layer_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Layer.js */ "./node_modules/ol/layer/Layer.js");
/* harmony import */ var _VectorRenderType_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./VectorRenderType.js */ "./node_modules/ol/layer/VectorRenderType.js");
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../obj.js */ "./node_modules/ol/obj.js");
/* harmony import */ var _style_Style_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../style/Style.js */ "./node_modules/ol/style/Style.js");
/**
 * @module ol/layer/Vector
 */







/**
 * @typedef {Object} Options
 * @property {number} [opacity=1] Opacity (0, 1).
 * @property {boolean} [visible=true] Visibility.
 * @property {import("../extent.js").Extent} [extent] The bounding extent for layer rendering.  The layer will not be
 * rendered outside of this extent.
 * @property {number} [zIndex] The z-index for layer rendering.  At rendering time, the layers
 * will be ordered, first by Z-index and then by position. When `undefined`, a `zIndex` of 0 is assumed
 * for layers that are added to the map's `layers` collection, or `Infinity` when the layer's `setMap()`
 * method was used.
 * @property {number} [minResolution] The minimum resolution (inclusive) at which this layer will be
 * visible.
 * @property {number} [maxResolution] The maximum resolution (exclusive) below which this layer will
 * be visible.
 * @property {import("../render.js").OrderFunction} [renderOrder] Render order. Function to be used when sorting
 * features before rendering. By default features are drawn in the order that they are created. Use
 * `null` to avoid the sort, but get an undefined draw order.
 * @property {number} [renderBuffer=100] The buffer in pixels around the viewport extent used by the
 * renderer when getting features from the vector source for the rendering or hit-detection.
 * Recommended value: the size of the largest symbol, line width or label.
 * @property {import("./VectorRenderType.js").default|string} [renderMode='vector'] Render mode for vector layers:
 *  * `'image'`: Vector layers are rendered as images. Great performance, but point symbols and
 *    texts are always rotated with the view and pixels are scaled during zoom animations.
 *  * `'vector'`: Vector layers are rendered as vectors. Most accurate rendering even during
 *    animations, but slower performance.
 * @property {import("../source/Vector.js").default} [source] Source.
 * @property {import("../PluggableMap.js").default} [map] Sets the layer as overlay on a map. The map will not manage
 * this layer in its layers collection, and the layer will be rendered on top. This is useful for
 * temporary layers. The standard way to add a layer to a map and have it managed by the map is to
 * use {@link module:ol/Map#addLayer}.
 * @property {boolean} [declutter=false] Declutter images and text. Decluttering is applied to all
 * image and text styles, and the priority is defined by the z-index of the style. Lower z-index
 * means higher priority.
 * @property {import("../style/Style.js").StyleLike} [style] Layer style. See
 * {@link module:ol/style} for default style which will be used if this is not defined.
 * @property {boolean} [updateWhileAnimating=false] When set to `true` and `renderMode`
 * is `vector`, feature batches will be recreated during animations. This means that no
 * vectors will be shown clipped, but the setting will have a performance impact for large
 * amounts of vector data. When set to `false`, batches will be recreated when no animation
 * is active.
 * @property {boolean} [updateWhileInteracting=false] When set to `true` and `renderMode`
 * is `vector`, feature batches will be recreated during interactions. See also
 * `updateWhileAnimating`.
 */


/**
 * @enum {string}
 * @private
 */
var Property = {
  RENDER_ORDER: 'renderOrder'
};


/**
 * @classdesc
 * Vector data that is rendered client-side.
 * Note that any property set in the options is set as a {@link module:ol/Object~BaseObject}
 * property on the layer object; for example, setting `title: 'My Title'` in the
 * options means that `title` is observable, and has get/set accessors.
 *
 * @api
 */
var VectorLayer = /*@__PURE__*/(function (Layer) {
  function VectorLayer(opt_options) {
    var options = opt_options ?
      opt_options : /** @type {Options} */ ({});

    var baseOptions = (0,_obj_js__WEBPACK_IMPORTED_MODULE_0__.assign)({}, options);

    delete baseOptions.style;
    delete baseOptions.renderBuffer;
    delete baseOptions.updateWhileAnimating;
    delete baseOptions.updateWhileInteracting;
    Layer.call(this, baseOptions);

    /**
    * @private
    * @type {boolean}
    */
    this.declutter_ = options.declutter !== undefined ? options.declutter : false;

    /**
    * @type {number}
    * @private
    */
    this.renderBuffer_ = options.renderBuffer !== undefined ?
      options.renderBuffer : 100;

    /**
    * User provided style.
    * @type {import("../style/Style.js").StyleLike}
    * @private
    */
    this.style_ = null;

    /**
    * Style function for use within the library.
    * @type {import("../style/Style.js").StyleFunction|undefined}
    * @private
    */
    this.styleFunction_ = undefined;

    this.setStyle(options.style);

    /**
    * @type {boolean}
    * @private
    */
    this.updateWhileAnimating_ = options.updateWhileAnimating !== undefined ?
      options.updateWhileAnimating : false;

    /**
    * @type {boolean}
    * @private
    */
    this.updateWhileInteracting_ = options.updateWhileInteracting !== undefined ?
      options.updateWhileInteracting : false;

    /**
    * @private
    * @type {import("./VectorTileRenderType.js").default|string}
    */
    this.renderMode_ = options.renderMode || _VectorRenderType_js__WEBPACK_IMPORTED_MODULE_1__["default"].VECTOR;

    /**
    * The layer type.
    * @protected
    * @type {import("../LayerType.js").default}
    */
    this.type = _LayerType_js__WEBPACK_IMPORTED_MODULE_2__["default"].VECTOR;

  }

  if ( Layer ) VectorLayer.__proto__ = Layer;
  VectorLayer.prototype = Object.create( Layer && Layer.prototype );
  VectorLayer.prototype.constructor = VectorLayer;

  /**
  * @return {boolean} Declutter.
  */
  VectorLayer.prototype.getDeclutter = function getDeclutter () {
    return this.declutter_;
  };

  /**
  * @param {boolean} declutter Declutter.
  */
  VectorLayer.prototype.setDeclutter = function setDeclutter (declutter) {
    this.declutter_ = declutter;
  };

  /**
  * @return {number|undefined} Render buffer.
  */
  VectorLayer.prototype.getRenderBuffer = function getRenderBuffer () {
    return this.renderBuffer_;
  };

  /**
  * @return {function(import("../Feature.js").default, import("../Feature.js").default): number|null|undefined} Render
  *     order.
  */
  VectorLayer.prototype.getRenderOrder = function getRenderOrder () {
    return (
    /** @type {import("../render.js").OrderFunction|null|undefined} */ (this.get(Property.RENDER_ORDER))
    );
  };

  /**
  * Get the style for features.  This returns whatever was passed to the `style`
  * option at construction or to the `setStyle` method.
  * @return {import("../style/Style.js").StyleLike}
  *     Layer style.
  * @api
  */
  VectorLayer.prototype.getStyle = function getStyle () {
    return this.style_;
  };

  /**
  * Get the style function.
  * @return {import("../style/Style.js").StyleFunction|undefined} Layer style function.
  * @api
  */
  VectorLayer.prototype.getStyleFunction = function getStyleFunction () {
    return this.styleFunction_;
  };

  /**
  * @return {boolean} Whether the rendered layer should be updated while
  *     animating.
  */
  VectorLayer.prototype.getUpdateWhileAnimating = function getUpdateWhileAnimating () {
    return this.updateWhileAnimating_;
  };

  /**
  * @return {boolean} Whether the rendered layer should be updated while
  *     interacting.
  */
  VectorLayer.prototype.getUpdateWhileInteracting = function getUpdateWhileInteracting () {
    return this.updateWhileInteracting_;
  };

  /**
  * @param {import("../render.js").OrderFunction|null|undefined} renderOrder
  *     Render order.
  */
  VectorLayer.prototype.setRenderOrder = function setRenderOrder (renderOrder) {
    this.set(Property.RENDER_ORDER, renderOrder);
  };

  /**
  * Set the style for features.  This can be a single style object, an array
  * of styles, or a function that takes a feature and resolution and returns
  * an array of styles. If it is `undefined` the default style is used. If
  * it is `null` the layer has no style (a `null` style), so only features
  * that have their own styles will be rendered in the layer. See
  * {@link module:ol/style} for information on the default style.
  * @param {import("../style/Style.js").default|Array<import("../style/Style.js").default>|import("../style/Style.js").StyleFunction|null|undefined} style Layer style.
  * @api
  */
  VectorLayer.prototype.setStyle = function setStyle (style) {
    this.style_ = style !== undefined ? style : _style_Style_js__WEBPACK_IMPORTED_MODULE_3__.createDefaultStyle;
    this.styleFunction_ = style === null ?
      undefined : (0,_style_Style_js__WEBPACK_IMPORTED_MODULE_3__.toFunction)(this.style_);
    this.changed();
  };

  /**
  * @return {import("./VectorRenderType.js").default|string} The render mode.
  */
  VectorLayer.prototype.getRenderMode = function getRenderMode () {
    return this.renderMode_;
  };

  return VectorLayer;
}(_Layer_js__WEBPACK_IMPORTED_MODULE_4__["default"]));


/**
 * Return the associated {@link module:ol/source/Vector vectorsource} of the layer.
 * @function
 * @return {import("../source/Vector.js").default} Source.
 * @api
 */
VectorLayer.prototype.getSource;


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (VectorLayer);

//# sourceMappingURL=Vector.js.map

/***/ }),

/***/ "./node_modules/ol/layer/VectorRenderType.js":
/*!***************************************************!*\
  !*** ./node_modules/ol/layer/VectorRenderType.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/layer/VectorRenderType
 */

/**
 * @enum {string}
 * Render mode for vector layers:
 *  * `'image'`: Vector layers are rendered as images. Great performance, but
 *    point symbols and texts are always rotated with the view and pixels are
 *    scaled during zoom animations.
 *  * `'vector'`: Vector layers are rendered as vectors. Most accurate rendering
 *    even during animations, but slower performance.
 * @api
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  IMAGE: 'image',
  VECTOR: 'vector'
});

//# sourceMappingURL=VectorRenderType.js.map

/***/ }),

/***/ "./node_modules/ol/loadingstrategy.js":
/*!********************************************!*\
  !*** ./node_modules/ol/loadingstrategy.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "all": () => (/* binding */ all),
/* harmony export */   "bbox": () => (/* binding */ bbox),
/* harmony export */   "tile": () => (/* binding */ tile)
/* harmony export */ });
/**
 * @module ol/loadingstrategy
 */


/**
 * Strategy function for loading all features with a single request.
 * @param {import("./extent.js").Extent} extent Extent.
 * @param {number} resolution Resolution.
 * @return {Array<import("./extent.js").Extent>} Extents.
 * @api
 */
function all(extent, resolution) {
  return [[-Infinity, -Infinity, Infinity, Infinity]];
}


/**
 * Strategy function for loading features based on the view's extent and
 * resolution.
 * @param {import("./extent.js").Extent} extent Extent.
 * @param {number} resolution Resolution.
 * @return {Array<import("./extent.js").Extent>} Extents.
 * @api
 */
function bbox(extent, resolution) {
  return [extent];
}


/**
 * Creates a strategy function for loading features based on a tile grid.
 * @param {import("./tilegrid/TileGrid.js").default} tileGrid Tile grid.
 * @return {function(import("./extent.js").Extent, number): Array<import("./extent.js").Extent>} Loading strategy.
 * @api
 */
function tile(tileGrid) {
  return (
    /**
     * @param {import("./extent.js").Extent} extent Extent.
     * @param {number} resolution Resolution.
     * @return {Array<import("./extent.js").Extent>} Extents.
     */
    function(extent, resolution) {
      var z = tileGrid.getZForResolution(resolution);
      var tileRange = tileGrid.getTileRangeForExtentAndZ(extent, z);
      /** @type {Array<import("./extent.js").Extent>} */
      var extents = [];
      /** @type {import("./tilecoord.js").TileCoord} */
      var tileCoord = [z, 0, 0];
      for (tileCoord[1] = tileRange.minX; tileCoord[1] <= tileRange.maxX; ++tileCoord[1]) {
        for (tileCoord[2] = tileRange.minY; tileCoord[2] <= tileRange.maxY; ++tileCoord[2]) {
          extents.push(tileGrid.getTileCoordExtent(tileCoord));
        }
      }
      return extents;
    }
  );
}

//# sourceMappingURL=loadingstrategy.js.map

/***/ }),

/***/ "./node_modules/ol/math.js":
/*!*********************************!*\
  !*** ./node_modules/ol/math.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "clamp": () => (/* binding */ clamp),
/* harmony export */   "cosh": () => (/* binding */ cosh),
/* harmony export */   "roundUpToPowerOfTwo": () => (/* binding */ roundUpToPowerOfTwo),
/* harmony export */   "squaredSegmentDistance": () => (/* binding */ squaredSegmentDistance),
/* harmony export */   "squaredDistance": () => (/* binding */ squaredDistance),
/* harmony export */   "solveLinearSystem": () => (/* binding */ solveLinearSystem),
/* harmony export */   "toDegrees": () => (/* binding */ toDegrees),
/* harmony export */   "toRadians": () => (/* binding */ toRadians),
/* harmony export */   "modulo": () => (/* binding */ modulo),
/* harmony export */   "lerp": () => (/* binding */ lerp)
/* harmony export */ });
/* harmony import */ var _asserts_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./asserts.js */ "./node_modules/ol/asserts.js");
/**
 * @module ol/math
 */


/**
 * Takes a number and clamps it to within the provided bounds.
 * @param {number} value The input number.
 * @param {number} min The minimum value to return.
 * @param {number} max The maximum value to return.
 * @return {number} The input number if it is within bounds, or the nearest
 *     number within the bounds.
 */
function clamp(value, min, max) {
  return Math.min(Math.max(value, min), max);
}


/**
 * Return the hyperbolic cosine of a given number. The method will use the
 * native `Math.cosh` function if it is available, otherwise the hyperbolic
 * cosine will be calculated via the reference implementation of the Mozilla
 * developer network.
 *
 * @param {number} x X.
 * @return {number} Hyperbolic cosine of x.
 */
var cosh = (function() {
  // Wrapped in a iife, to save the overhead of checking for the native
  // implementation on every invocation.
  var cosh;
  if ('cosh' in Math) {
    // The environment supports the native Math.cosh function, use it…
    cosh = Math.cosh;
  } else {
    // … else, use the reference implementation of MDN:
    cosh = function(x) {
      var y = /** @type {Math} */ (Math).exp(x);
      return (y + 1 / y) / 2;
    };
  }
  return cosh;
}());


/**
 * @param {number} x X.
 * @return {number} The smallest power of two greater than or equal to x.
 */
function roundUpToPowerOfTwo(x) {
  (0,_asserts_js__WEBPACK_IMPORTED_MODULE_0__.assert)(0 < x, 29); // `x` must be greater than `0`
  return Math.pow(2, Math.ceil(Math.log(x) / Math.LN2));
}


/**
 * Returns the square of the closest distance between the point (x, y) and the
 * line segment (x1, y1) to (x2, y2).
 * @param {number} x X.
 * @param {number} y Y.
 * @param {number} x1 X1.
 * @param {number} y1 Y1.
 * @param {number} x2 X2.
 * @param {number} y2 Y2.
 * @return {number} Squared distance.
 */
function squaredSegmentDistance(x, y, x1, y1, x2, y2) {
  var dx = x2 - x1;
  var dy = y2 - y1;
  if (dx !== 0 || dy !== 0) {
    var t = ((x - x1) * dx + (y - y1) * dy) / (dx * dx + dy * dy);
    if (t > 1) {
      x1 = x2;
      y1 = y2;
    } else if (t > 0) {
      x1 += dx * t;
      y1 += dy * t;
    }
  }
  return squaredDistance(x, y, x1, y1);
}


/**
 * Returns the square of the distance between the points (x1, y1) and (x2, y2).
 * @param {number} x1 X1.
 * @param {number} y1 Y1.
 * @param {number} x2 X2.
 * @param {number} y2 Y2.
 * @return {number} Squared distance.
 */
function squaredDistance(x1, y1, x2, y2) {
  var dx = x2 - x1;
  var dy = y2 - y1;
  return dx * dx + dy * dy;
}


/**
 * Solves system of linear equations using Gaussian elimination method.
 *
 * @param {Array<Array<number>>} mat Augmented matrix (n x n + 1 column)
 *                                     in row-major order.
 * @return {Array<number>} The resulting vector.
 */
function solveLinearSystem(mat) {
  var n = mat.length;

  for (var i = 0; i < n; i++) {
    // Find max in the i-th column (ignoring i - 1 first rows)
    var maxRow = i;
    var maxEl = Math.abs(mat[i][i]);
    for (var r = i + 1; r < n; r++) {
      var absValue = Math.abs(mat[r][i]);
      if (absValue > maxEl) {
        maxEl = absValue;
        maxRow = r;
      }
    }

    if (maxEl === 0) {
      return null; // matrix is singular
    }

    // Swap max row with i-th (current) row
    var tmp = mat[maxRow];
    mat[maxRow] = mat[i];
    mat[i] = tmp;

    // Subtract the i-th row to make all the remaining rows 0 in the i-th column
    for (var j = i + 1; j < n; j++) {
      var coef = -mat[j][i] / mat[i][i];
      for (var k = i; k < n + 1; k++) {
        if (i == k) {
          mat[j][k] = 0;
        } else {
          mat[j][k] += coef * mat[i][k];
        }
      }
    }
  }

  // Solve Ax=b for upper triangular matrix A (mat)
  var x = new Array(n);
  for (var l = n - 1; l >= 0; l--) {
    x[l] = mat[l][n] / mat[l][l];
    for (var m = l - 1; m >= 0; m--) {
      mat[m][n] -= mat[m][l] * x[l];
    }
  }
  return x;
}


/**
 * Converts radians to to degrees.
 *
 * @param {number} angleInRadians Angle in radians.
 * @return {number} Angle in degrees.
 */
function toDegrees(angleInRadians) {
  return angleInRadians * 180 / Math.PI;
}


/**
 * Converts degrees to radians.
 *
 * @param {number} angleInDegrees Angle in degrees.
 * @return {number} Angle in radians.
 */
function toRadians(angleInDegrees) {
  return angleInDegrees * Math.PI / 180;
}

/**
 * Returns the modulo of a / b, depending on the sign of b.
 *
 * @param {number} a Dividend.
 * @param {number} b Divisor.
 * @return {number} Modulo.
 */
function modulo(a, b) {
  var r = a % b;
  return r * b < 0 ? r + b : r;
}

/**
 * Calculates the linearly interpolated value of x between a and b.
 *
 * @param {number} a Number
 * @param {number} b Number
 * @param {number} x Value to be interpolated.
 * @return {number} Interpolated value.
 */
function lerp(a, b, x) {
  return a + x * (b - a);
}

//# sourceMappingURL=math.js.map

/***/ }),

/***/ "./node_modules/ol/obj.js":
/*!********************************!*\
  !*** ./node_modules/ol/obj.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "assign": () => (/* binding */ assign),
/* harmony export */   "clear": () => (/* binding */ clear),
/* harmony export */   "getValues": () => (/* binding */ getValues),
/* harmony export */   "isEmpty": () => (/* binding */ isEmpty)
/* harmony export */ });
/**
 * @module ol/obj
 */


/**
 * Polyfill for Object.assign().  Assigns enumerable and own properties from
 * one or more source objects to a target object.
 * See https://developer.mozilla.org/en/docs/Web/JavaScript/Reference/Global_Objects/Object/assign.
 *
 * @param {!Object} target The target object.
 * @param {...Object} var_sources The source object(s).
 * @return {!Object} The modified target object.
 */
var assign = (typeof Object.assign === 'function') ? Object.assign : function(target, var_sources) {
  var arguments$1 = arguments;

  if (target === undefined || target === null) {
    throw new TypeError('Cannot convert undefined or null to object');
  }

  var output = Object(target);
  for (var i = 1, ii = arguments.length; i < ii; ++i) {
    var source = arguments$1[i];
    if (source !== undefined && source !== null) {
      for (var key in source) {
        if (source.hasOwnProperty(key)) {
          output[key] = source[key];
        }
      }
    }
  }
  return output;
};


/**
 * Removes all properties from an object.
 * @param {Object} object The object to clear.
 */
function clear(object) {
  for (var property in object) {
    delete object[property];
  }
}


/**
 * Get an array of property values from an object.
 * @param {Object<K,V>} object The object from which to get the values.
 * @return {!Array<V>} The property values.
 * @template K,V
 */
function getValues(object) {
  var values = [];
  for (var property in object) {
    values.push(object[property]);
  }
  return values;
}


/**
 * Determine if an object has any properties.
 * @param {Object} object The object to check.
 * @return {boolean} The object is empty.
 */
function isEmpty(object) {
  var property;
  for (property in object) {
    return false;
  }
  return !property;
}

//# sourceMappingURL=obj.js.map

/***/ }),

/***/ "./node_modules/ol/proj.js":
/*!*********************************!*\
  !*** ./node_modules/ol/proj.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "METERS_PER_UNIT": () => (/* reexport safe */ _proj_Units_js__WEBPACK_IMPORTED_MODULE_0__.METERS_PER_UNIT),
/* harmony export */   "Projection": () => (/* reexport safe */ _proj_Projection_js__WEBPACK_IMPORTED_MODULE_1__["default"]),
/* harmony export */   "cloneTransform": () => (/* binding */ cloneTransform),
/* harmony export */   "identityTransform": () => (/* binding */ identityTransform),
/* harmony export */   "addProjection": () => (/* binding */ addProjection),
/* harmony export */   "addProjections": () => (/* binding */ addProjections),
/* harmony export */   "get": () => (/* binding */ get),
/* harmony export */   "getPointResolution": () => (/* binding */ getPointResolution),
/* harmony export */   "addEquivalentProjections": () => (/* binding */ addEquivalentProjections),
/* harmony export */   "addEquivalentTransforms": () => (/* binding */ addEquivalentTransforms),
/* harmony export */   "clearAllProjections": () => (/* binding */ clearAllProjections),
/* harmony export */   "createProjection": () => (/* binding */ createProjection),
/* harmony export */   "createTransformFromCoordinateTransform": () => (/* binding */ createTransformFromCoordinateTransform),
/* harmony export */   "addCoordinateTransforms": () => (/* binding */ addCoordinateTransforms),
/* harmony export */   "fromLonLat": () => (/* binding */ fromLonLat),
/* harmony export */   "toLonLat": () => (/* binding */ toLonLat),
/* harmony export */   "equivalent": () => (/* binding */ equivalent),
/* harmony export */   "getTransformFromProjections": () => (/* binding */ getTransformFromProjections),
/* harmony export */   "getTransform": () => (/* binding */ getTransform),
/* harmony export */   "transform": () => (/* binding */ transform),
/* harmony export */   "transformExtent": () => (/* binding */ transformExtent),
/* harmony export */   "transformWithProjections": () => (/* binding */ transformWithProjections),
/* harmony export */   "addCommon": () => (/* binding */ addCommon)
/* harmony export */ });
/* harmony import */ var _sphere_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./sphere.js */ "./node_modules/ol/sphere.js");
/* harmony import */ var _extent_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./extent.js */ "./node_modules/ol/extent.js");
/* harmony import */ var _math_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./math.js */ "./node_modules/ol/math.js");
/* harmony import */ var _proj_epsg3857_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./proj/epsg3857.js */ "./node_modules/ol/proj/epsg3857.js");
/* harmony import */ var _proj_epsg4326_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./proj/epsg4326.js */ "./node_modules/ol/proj/epsg4326.js");
/* harmony import */ var _proj_Projection_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./proj/Projection.js */ "./node_modules/ol/proj/Projection.js");
/* harmony import */ var _proj_Units_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./proj/Units.js */ "./node_modules/ol/proj/Units.js");
/* harmony import */ var _proj_projections_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./proj/projections.js */ "./node_modules/ol/proj/projections.js");
/* harmony import */ var _proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./proj/transforms.js */ "./node_modules/ol/proj/transforms.js");
/**
 * @module ol/proj
 */

/**
 * The ol/proj module stores:
 * * a list of {@link module:ol/proj/Projection}
 * objects, one for each projection supported by the application
 * * a list of transform functions needed to convert coordinates in one projection
 * into another.
 *
 * The static functions are the methods used to maintain these.
 * Each transform function can handle not only simple coordinate pairs, but also
 * large arrays of coordinates such as vector geometries.
 *
 * When loaded, the library adds projection objects for EPSG:4326 (WGS84
 * geographic coordinates) and EPSG:3857 (Web or Spherical Mercator, as used
 * for example by Bing Maps or OpenStreetMap), together with the relevant
 * transform functions.
 *
 * Additional transforms may be added by using the http://proj4js.org/
 * library (version 2.2 or later). You can use the full build supplied by
 * Proj4js, or create a custom build to support those projections you need; see
 * the Proj4js website for how to do this. You also need the Proj4js definitions
 * for the required projections. These definitions can be obtained from
 * https://epsg.io/, and are a JS function, so can be loaded in a script
 * tag (as in the examples) or pasted into your application.
 *
 * After all required projection definitions are added to proj4's registry (by
 * using `proj4.defs()`), simply call `register(proj4)` from the `ol/proj/proj4`
 * package. Existing transforms are not changed by this function. See
 * examples/wms-image-custom-proj for an example of this.
 *
 * Additional projection definitions can be registered with `proj4.defs()` any
 * time. Just make sure to call `register(proj4)` again; for example, with user-supplied data where you don't
 * know in advance what projections are needed, you can initially load minimal
 * support and then load whichever are requested.
 *
 * Note that Proj4js does not support projection extents. If you want to add
 * one for creating default tile grids, you can add it after the Projection
 * object has been created with `setExtent`, for example,
 * `get('EPSG:1234').setExtent(extent)`.
 *
 * In addition to Proj4js support, any transform functions can be added with
 * {@link module:ol/proj~addCoordinateTransforms}. To use this, you must first create
 * a {@link module:ol/proj/Projection} object for the new projection and add it with
 * {@link module:ol/proj~addProjection}. You can then add the forward and inverse
 * functions with {@link module:ol/proj~addCoordinateTransforms}. See
 * examples/wms-custom-proj for an example of this.
 *
 * Note that if no transforms are needed and you only need to define the
 * projection, just add a {@link module:ol/proj/Projection} with
 * {@link module:ol/proj~addProjection}. See examples/wms-no-proj for an example of
 * this.
 */











/**
 * A projection as {@link module:ol/proj/Projection}, SRS identifier
 * string or undefined.
 * @typedef {Projection|string|undefined} ProjectionLike
 * @api
 */


/**
 * A transform function accepts an array of input coordinate values, an optional
 * output array, and an optional dimension (default should be 2).  The function
 * transforms the input coordinate values, populates the output array, and
 * returns the output array.
 *
 * @typedef {function(Array<number>, Array<number>=, number=): Array<number>} TransformFunction
 * @api
 */






/**
 * @param {Array<number>} input Input coordinate array.
 * @param {Array<number>=} opt_output Output array of coordinate values.
 * @param {number=} opt_dimension Dimension.
 * @return {Array<number>} Output coordinate array (new array, same coordinate
 *     values).
 */
function cloneTransform(input, opt_output, opt_dimension) {
  var output;
  if (opt_output !== undefined) {
    for (var i = 0, ii = input.length; i < ii; ++i) {
      opt_output[i] = input[i];
    }
    output = opt_output;
  } else {
    output = input.slice();
  }
  return output;
}


/**
 * @param {Array<number>} input Input coordinate array.
 * @param {Array<number>=} opt_output Output array of coordinate values.
 * @param {number=} opt_dimension Dimension.
 * @return {Array<number>} Input coordinate array (same array as input).
 */
function identityTransform(input, opt_output, opt_dimension) {
  if (opt_output !== undefined && input !== opt_output) {
    for (var i = 0, ii = input.length; i < ii; ++i) {
      opt_output[i] = input[i];
    }
    input = opt_output;
  }
  return input;
}


/**
 * Add a Projection object to the list of supported projections that can be
 * looked up by their code.
 *
 * @param {Projection} projection Projection instance.
 * @api
 */
function addProjection(projection) {
  _proj_projections_js__WEBPACK_IMPORTED_MODULE_2__.add(projection.getCode(), projection);
  (0,_proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__.add)(projection, projection, cloneTransform);
}


/**
 * @param {Array<Projection>} projections Projections.
 */
function addProjections(projections) {
  projections.forEach(addProjection);
}


/**
 * Fetches a Projection object for the code specified.
 *
 * @param {ProjectionLike} projectionLike Either a code string which is
 *     a combination of authority and identifier such as "EPSG:4326", or an
 *     existing projection object, or undefined.
 * @return {Projection} Projection object, or null if not in list.
 * @api
 */
function get(projectionLike) {
  return typeof projectionLike === 'string' ?
    _proj_projections_js__WEBPACK_IMPORTED_MODULE_2__.get(/** @type {string} */ (projectionLike)) :
    (/** @type {Projection} */ (projectionLike) || null);
}


/**
 * Get the resolution of the point in degrees or distance units.
 * For projections with degrees as the unit this will simply return the
 * provided resolution. For other projections the point resolution is
 * by default estimated by transforming the 'point' pixel to EPSG:4326,
 * measuring its width and height on the normal sphere,
 * and taking the average of the width and height.
 * A custom function can be provided for a specific projection, either
 * by setting the `getPointResolution` option in the
 * {@link module:ol/proj/Projection~Projection} constructor or by using
 * {@link module:ol/proj/Projection~Projection#setGetPointResolution} to change an existing
 * projection object.
 * @param {ProjectionLike} projection The projection.
 * @param {number} resolution Nominal resolution in projection units.
 * @param {import("./coordinate.js").Coordinate} point Point to find adjusted resolution at.
 * @param {Units=} opt_units Units to get the point resolution in.
 * Default is the projection's units.
 * @return {number} Point resolution.
 * @api
 */
function getPointResolution(projection, resolution, point, opt_units) {
  projection = get(projection);
  var pointResolution;
  var getter = projection.getPointResolutionFunc();
  if (getter) {
    pointResolution = getter(resolution, point);
  } else {
    var units = projection.getUnits();
    if (units == _proj_Units_js__WEBPACK_IMPORTED_MODULE_0__["default"].DEGREES && !opt_units || opt_units == _proj_Units_js__WEBPACK_IMPORTED_MODULE_0__["default"].DEGREES) {
      pointResolution = resolution;
    } else {
      // Estimate point resolution by transforming the center pixel to EPSG:4326,
      // measuring its width and height on the normal sphere, and taking the
      // average of the width and height.
      var toEPSG4326 = getTransformFromProjections(projection, get('EPSG:4326'));
      var vertices = [
        point[0] - resolution / 2, point[1],
        point[0] + resolution / 2, point[1],
        point[0], point[1] - resolution / 2,
        point[0], point[1] + resolution / 2
      ];
      vertices = toEPSG4326(vertices, vertices, 2);
      var width = (0,_sphere_js__WEBPACK_IMPORTED_MODULE_4__.getDistance)(vertices.slice(0, 2), vertices.slice(2, 4));
      var height = (0,_sphere_js__WEBPACK_IMPORTED_MODULE_4__.getDistance)(vertices.slice(4, 6), vertices.slice(6, 8));
      pointResolution = (width + height) / 2;
      var metersPerUnit = opt_units ?
        _proj_Units_js__WEBPACK_IMPORTED_MODULE_0__.METERS_PER_UNIT[opt_units] :
        projection.getMetersPerUnit();
      if (metersPerUnit !== undefined) {
        pointResolution /= metersPerUnit;
      }
    }
  }
  return pointResolution;
}


/**
 * Registers transformation functions that don't alter coordinates. Those allow
 * to transform between projections with equal meaning.
 *
 * @param {Array<Projection>} projections Projections.
 * @api
 */
function addEquivalentProjections(projections) {
  addProjections(projections);
  projections.forEach(function(source) {
    projections.forEach(function(destination) {
      if (source !== destination) {
        (0,_proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__.add)(source, destination, cloneTransform);
      }
    });
  });
}


/**
 * Registers transformation functions to convert coordinates in any projection
 * in projection1 to any projection in projection2.
 *
 * @param {Array<Projection>} projections1 Projections with equal
 *     meaning.
 * @param {Array<Projection>} projections2 Projections with equal
 *     meaning.
 * @param {TransformFunction} forwardTransform Transformation from any
 *   projection in projection1 to any projection in projection2.
 * @param {TransformFunction} inverseTransform Transform from any projection
 *   in projection2 to any projection in projection1..
 */
function addEquivalentTransforms(projections1, projections2, forwardTransform, inverseTransform) {
  projections1.forEach(function(projection1) {
    projections2.forEach(function(projection2) {
      (0,_proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__.add)(projection1, projection2, forwardTransform);
      (0,_proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__.add)(projection2, projection1, inverseTransform);
    });
  });
}


/**
 * Clear all cached projections and transforms.
 */
function clearAllProjections() {
  _proj_projections_js__WEBPACK_IMPORTED_MODULE_2__.clear();
  (0,_proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__.clear)();
}


/**
 * @param {Projection|string|undefined} projection Projection.
 * @param {string} defaultCode Default code.
 * @return {Projection} Projection.
 */
function createProjection(projection, defaultCode) {
  if (!projection) {
    return get(defaultCode);
  } else if (typeof projection === 'string') {
    return get(projection);
  } else {
    return (
      /** @type {Projection} */ (projection)
    );
  }
}


/**
 * Creates a {@link module:ol/proj~TransformFunction} from a simple 2D coordinate transform
 * function.
 * @param {function(import("./coordinate.js").Coordinate): import("./coordinate.js").Coordinate} coordTransform Coordinate
 *     transform.
 * @return {TransformFunction} Transform function.
 */
function createTransformFromCoordinateTransform(coordTransform) {
  return (
    /**
     * @param {Array<number>} input Input.
     * @param {Array<number>=} opt_output Output.
     * @param {number=} opt_dimension Dimension.
     * @return {Array<number>} Output.
     */
    function(input, opt_output, opt_dimension) {
      var length = input.length;
      var dimension = opt_dimension !== undefined ? opt_dimension : 2;
      var output = opt_output !== undefined ? opt_output : new Array(length);
      for (var i = 0; i < length; i += dimension) {
        var point = coordTransform([input[i], input[i + 1]]);
        output[i] = point[0];
        output[i + 1] = point[1];
        for (var j = dimension - 1; j >= 2; --j) {
          output[i + j] = input[i + j];
        }
      }
      return output;
    });
}


/**
 * Registers coordinate transform functions to convert coordinates between the
 * source projection and the destination projection.
 * The forward and inverse functions convert coordinate pairs; this function
 * converts these into the functions used internally which also handle
 * extents and coordinate arrays.
 *
 * @param {ProjectionLike} source Source projection.
 * @param {ProjectionLike} destination Destination projection.
 * @param {function(import("./coordinate.js").Coordinate): import("./coordinate.js").Coordinate} forward The forward transform
 *     function (that is, from the source projection to the destination
 *     projection) that takes a {@link module:ol/coordinate~Coordinate} as argument and returns
 *     the transformed {@link module:ol/coordinate~Coordinate}.
 * @param {function(import("./coordinate.js").Coordinate): import("./coordinate.js").Coordinate} inverse The inverse transform
 *     function (that is, from the destination projection to the source
 *     projection) that takes a {@link module:ol/coordinate~Coordinate} as argument and returns
 *     the transformed {@link module:ol/coordinate~Coordinate}.
 * @api
 */
function addCoordinateTransforms(source, destination, forward, inverse) {
  var sourceProj = get(source);
  var destProj = get(destination);
  (0,_proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__.add)(sourceProj, destProj, createTransformFromCoordinateTransform(forward));
  (0,_proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__.add)(destProj, sourceProj, createTransformFromCoordinateTransform(inverse));
}


/**
 * Transforms a coordinate from longitude/latitude to a different projection.
 * @param {import("./coordinate.js").Coordinate} coordinate Coordinate as longitude and latitude, i.e.
 *     an array with longitude as 1st and latitude as 2nd element.
 * @param {ProjectionLike=} opt_projection Target projection. The
 *     default is Web Mercator, i.e. 'EPSG:3857'.
 * @return {import("./coordinate.js").Coordinate} Coordinate projected to the target projection.
 * @api
 */
function fromLonLat(coordinate, opt_projection) {
  return transform(coordinate, 'EPSG:4326',
    opt_projection !== undefined ? opt_projection : 'EPSG:3857');
}


/**
 * Transforms a coordinate to longitude/latitude.
 * @param {import("./coordinate.js").Coordinate} coordinate Projected coordinate.
 * @param {ProjectionLike=} opt_projection Projection of the coordinate.
 *     The default is Web Mercator, i.e. 'EPSG:3857'.
 * @return {import("./coordinate.js").Coordinate} Coordinate as longitude and latitude, i.e. an array
 *     with longitude as 1st and latitude as 2nd element.
 * @api
 */
function toLonLat(coordinate, opt_projection) {
  var lonLat = transform(coordinate,
    opt_projection !== undefined ? opt_projection : 'EPSG:3857', 'EPSG:4326');
  var lon = lonLat[0];
  if (lon < -180 || lon > 180) {
    lonLat[0] = (0,_math_js__WEBPACK_IMPORTED_MODULE_5__.modulo)(lon + 180, 360) - 180;
  }
  return lonLat;
}


/**
 * Checks if two projections are the same, that is every coordinate in one
 * projection does represent the same geographic point as the same coordinate in
 * the other projection.
 *
 * @param {Projection} projection1 Projection 1.
 * @param {Projection} projection2 Projection 2.
 * @return {boolean} Equivalent.
 * @api
 */
function equivalent(projection1, projection2) {
  if (projection1 === projection2) {
    return true;
  }
  var equalUnits = projection1.getUnits() === projection2.getUnits();
  if (projection1.getCode() === projection2.getCode()) {
    return equalUnits;
  } else {
    var transformFunc = getTransformFromProjections(projection1, projection2);
    return transformFunc === cloneTransform && equalUnits;
  }
}


/**
 * Searches in the list of transform functions for the function for converting
 * coordinates from the source projection to the destination projection.
 *
 * @param {Projection} sourceProjection Source Projection object.
 * @param {Projection} destinationProjection Destination Projection
 *     object.
 * @return {TransformFunction} Transform function.
 */
function getTransformFromProjections(sourceProjection, destinationProjection) {
  var sourceCode = sourceProjection.getCode();
  var destinationCode = destinationProjection.getCode();
  var transformFunc = (0,_proj_transforms_js__WEBPACK_IMPORTED_MODULE_3__.get)(sourceCode, destinationCode);
  if (!transformFunc) {
    transformFunc = identityTransform;
  }
  return transformFunc;
}


/**
 * Given the projection-like objects, searches for a transformation
 * function to convert a coordinates array from the source projection to the
 * destination projection.
 *
 * @param {ProjectionLike} source Source.
 * @param {ProjectionLike} destination Destination.
 * @return {TransformFunction} Transform function.
 * @api
 */
function getTransform(source, destination) {
  var sourceProjection = get(source);
  var destinationProjection = get(destination);
  return getTransformFromProjections(sourceProjection, destinationProjection);
}


/**
 * Transforms a coordinate from source projection to destination projection.
 * This returns a new coordinate (and does not modify the original).
 *
 * See {@link module:ol/proj~transformExtent} for extent transformation.
 * See the transform method of {@link module:ol/geom/Geometry~Geometry} and its
 * subclasses for geometry transforms.
 *
 * @param {import("./coordinate.js").Coordinate} coordinate Coordinate.
 * @param {ProjectionLike} source Source projection-like.
 * @param {ProjectionLike} destination Destination projection-like.
 * @return {import("./coordinate.js").Coordinate} Coordinate.
 * @api
 */
function transform(coordinate, source, destination) {
  var transformFunc = getTransform(source, destination);
  return transformFunc(coordinate, undefined, coordinate.length);
}


/**
 * Transforms an extent from source projection to destination projection.  This
 * returns a new extent (and does not modify the original).
 *
 * @param {import("./extent.js").Extent} extent The extent to transform.
 * @param {ProjectionLike} source Source projection-like.
 * @param {ProjectionLike} destination Destination projection-like.
 * @return {import("./extent.js").Extent} The transformed extent.
 * @api
 */
function transformExtent(extent, source, destination) {
  var transformFunc = getTransform(source, destination);
  return (0,_extent_js__WEBPACK_IMPORTED_MODULE_6__.applyTransform)(extent, transformFunc);
}


/**
 * Transforms the given point to the destination projection.
 *
 * @param {import("./coordinate.js").Coordinate} point Point.
 * @param {Projection} sourceProjection Source projection.
 * @param {Projection} destinationProjection Destination projection.
 * @return {import("./coordinate.js").Coordinate} Point.
 */
function transformWithProjections(point, sourceProjection, destinationProjection) {
  var transformFunc = getTransformFromProjections(sourceProjection, destinationProjection);
  return transformFunc(point);
}

/**
 * Add transforms to and from EPSG:4326 and EPSG:3857.  This function is called
 * by when this module is executed and should only need to be called again after
 * `clearAllProjections()` is called (e.g. in tests).
 */
function addCommon() {
  // Add transformations that don't alter coordinates to convert within set of
  // projections with equal meaning.
  addEquivalentProjections(_proj_epsg3857_js__WEBPACK_IMPORTED_MODULE_7__.PROJECTIONS);
  addEquivalentProjections(_proj_epsg4326_js__WEBPACK_IMPORTED_MODULE_8__.PROJECTIONS);
  // Add transformations to convert EPSG:4326 like coordinates to EPSG:3857 like
  // coordinates and back.
  addEquivalentTransforms(_proj_epsg4326_js__WEBPACK_IMPORTED_MODULE_8__.PROJECTIONS, _proj_epsg3857_js__WEBPACK_IMPORTED_MODULE_7__.PROJECTIONS, _proj_epsg3857_js__WEBPACK_IMPORTED_MODULE_7__.fromEPSG4326, _proj_epsg3857_js__WEBPACK_IMPORTED_MODULE_7__.toEPSG4326);
}

addCommon();

//# sourceMappingURL=proj.js.map

/***/ }),

/***/ "./node_modules/ol/proj/Projection.js":
/*!********************************************!*\
  !*** ./node_modules/ol/proj/Projection.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Units_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Units.js */ "./node_modules/ol/proj/Units.js");
/**
 * @module ol/proj/Projection
 */



/**
 * @typedef {Object} Options
 * @property {string} code The SRS identifier code, e.g. `EPSG:4326`.
 * @property {import("./Units.js").default|string} [units] Units. Required unless a
 * proj4 projection is defined for `code`.
 * @property {import("../extent.js").Extent} [extent] The validity extent for the SRS.
 * @property {string} [axisOrientation='enu'] The axis orientation as specified in Proj4.
 * @property {boolean} [global=false] Whether the projection is valid for the whole globe.
 * @property {number} [metersPerUnit] The meters per unit for the SRS.
 * If not provided, the `units` are used to get the meters per unit from the {@link module:ol/proj/Units~METERS_PER_UNIT}
 * lookup table.
 * @property {import("../extent.js").Extent} [worldExtent] The world extent for the SRS.
 * @property {function(number, import("../coordinate.js").Coordinate):number} [getPointResolution]
 * Function to determine resolution at a point. The function is called with a
 * `{number}` view resolution and an `{import("../coordinate.js").Coordinate}` as arguments, and returns
 * the `{number}` resolution at the passed coordinate. If this is `undefined`,
 * the default {@link module:ol/proj#getPointResolution} function will be used.
 */


/**
 * @classdesc
 * Projection definition class. One of these is created for each projection
 * supported in the application and stored in the {@link module:ol/proj} namespace.
 * You can use these in applications, but this is not required, as API params
 * and options use {@link module:ol/proj~ProjectionLike} which means the simple string
 * code will suffice.
 *
 * You can use {@link module:ol/proj~get} to retrieve the object for a particular
 * projection.
 *
 * The library includes definitions for `EPSG:4326` and `EPSG:3857`, together
 * with the following aliases:
 * * `EPSG:4326`: CRS:84, urn:ogc:def:crs:EPSG:6.6:4326,
 *     urn:ogc:def:crs:OGC:1.3:CRS84, urn:ogc:def:crs:OGC:2:84,
 *     http://www.opengis.net/gml/srs/epsg.xml#4326,
 *     urn:x-ogc:def:crs:EPSG:4326
 * * `EPSG:3857`: EPSG:102100, EPSG:102113, EPSG:900913,
 *     urn:ogc:def:crs:EPSG:6.18:3:3857,
 *     http://www.opengis.net/gml/srs/epsg.xml#3857
 *
 * If you use [proj4js](https://github.com/proj4js/proj4js), aliases can
 * be added using `proj4.defs()`. After all required projection definitions are
 * added, call the {@link module:ol/proj/proj4~register} function.
 *
 * @api
 */
var Projection = function Projection(options) {
  /**
   * @private
   * @type {string}
   */
  this.code_ = options.code;

  /**
   * Units of projected coordinates. When set to `TILE_PIXELS`, a
   * `this.extent_` and `this.worldExtent_` must be configured properly for each
   * tile.
   * @private
   * @type {import("./Units.js").default}
   */
  this.units_ = /** @type {import("./Units.js").default} */ (options.units);

  /**
   * Validity extent of the projection in projected coordinates. For projections
   * with `TILE_PIXELS` units, this is the extent of the tile in
   * tile pixel space.
   * @private
   * @type {import("../extent.js").Extent}
   */
  this.extent_ = options.extent !== undefined ? options.extent : null;

  /**
   * Extent of the world in EPSG:4326. For projections with
   * `TILE_PIXELS` units, this is the extent of the tile in
   * projected coordinate space.
   * @private
   * @type {import("../extent.js").Extent}
   */
  this.worldExtent_ = options.worldExtent !== undefined ?
    options.worldExtent : null;

  /**
   * @private
   * @type {string}
   */
  this.axisOrientation_ = options.axisOrientation !== undefined ?
    options.axisOrientation : 'enu';

  /**
   * @private
   * @type {boolean}
   */
  this.global_ = options.global !== undefined ? options.global : false;

  /**
   * @private
   * @type {boolean}
   */
  this.canWrapX_ = !!(this.global_ && this.extent_);

  /**
   * @private
   * @type {function(number, import("../coordinate.js").Coordinate):number|undefined}
   */
  this.getPointResolutionFunc_ = options.getPointResolution;

  /**
   * @private
   * @type {import("../tilegrid/TileGrid.js").default}
   */
  this.defaultTileGrid_ = null;

  /**
   * @private
   * @type {number|undefined}
   */
  this.metersPerUnit_ = options.metersPerUnit;
};

/**
 * @return {boolean} The projection is suitable for wrapping the x-axis
 */
Projection.prototype.canWrapX = function canWrapX () {
  return this.canWrapX_;
};

/**
 * Get the code for this projection, e.g. 'EPSG:4326'.
 * @return {string} Code.
 * @api
 */
Projection.prototype.getCode = function getCode () {
  return this.code_;
};

/**
 * Get the validity extent for this projection.
 * @return {import("../extent.js").Extent} Extent.
 * @api
 */
Projection.prototype.getExtent = function getExtent () {
  return this.extent_;
};

/**
 * Get the units of this projection.
 * @return {import("./Units.js").default} Units.
 * @api
 */
Projection.prototype.getUnits = function getUnits () {
  return this.units_;
};

/**
 * Get the amount of meters per unit of this projection.If the projection is
 * not configured with `metersPerUnit` or a units identifier, the return is
 * `undefined`.
 * @return {number|undefined} Meters.
 * @api
 */
Projection.prototype.getMetersPerUnit = function getMetersPerUnit () {
  return this.metersPerUnit_ || _Units_js__WEBPACK_IMPORTED_MODULE_0__.METERS_PER_UNIT[this.units_];
};

/**
 * Get the world extent for this projection.
 * @return {import("../extent.js").Extent} Extent.
 * @api
 */
Projection.prototype.getWorldExtent = function getWorldExtent () {
  return this.worldExtent_;
};

/**
 * Get the axis orientation of this projection.
 * Example values are:
 * enu - the default easting, northing, elevation.
 * neu - northing, easting, up - useful for "lat/long" geographic coordinates,
 *   or south orientated transverse mercator.
 * wnu - westing, northing, up - some planetary coordinate systems have
 *   "west positive" coordinate systems
 * @return {string} Axis orientation.
 * @api
 */
Projection.prototype.getAxisOrientation = function getAxisOrientation () {
  return this.axisOrientation_;
};

/**
 * Is this projection a global projection which spans the whole world?
 * @return {boolean} Whether the projection is global.
 * @api
 */
Projection.prototype.isGlobal = function isGlobal () {
  return this.global_;
};

/**
 * Set if the projection is a global projection which spans the whole world
 * @param {boolean} global Whether the projection is global.
 * @api
 */
Projection.prototype.setGlobal = function setGlobal (global) {
  this.global_ = global;
  this.canWrapX_ = !!(global && this.extent_);
};

/**
 * @return {import("../tilegrid/TileGrid.js").default} The default tile grid.
 */
Projection.prototype.getDefaultTileGrid = function getDefaultTileGrid () {
  return this.defaultTileGrid_;
};

/**
 * @param {import("../tilegrid/TileGrid.js").default} tileGrid The default tile grid.
 */
Projection.prototype.setDefaultTileGrid = function setDefaultTileGrid (tileGrid) {
  this.defaultTileGrid_ = tileGrid;
};

/**
 * Set the validity extent for this projection.
 * @param {import("../extent.js").Extent} extent Extent.
 * @api
 */
Projection.prototype.setExtent = function setExtent (extent) {
  this.extent_ = extent;
  this.canWrapX_ = !!(this.global_ && extent);
};

/**
 * Set the world extent for this projection.
 * @param {import("../extent.js").Extent} worldExtent World extent
 *   [minlon, minlat, maxlon, maxlat].
 * @api
 */
Projection.prototype.setWorldExtent = function setWorldExtent (worldExtent) {
  this.worldExtent_ = worldExtent;
};

/**
 * Set the getPointResolution function (see {@link module:ol/proj~getPointResolution}
 * for this projection.
 * @param {function(number, import("../coordinate.js").Coordinate):number} func Function
 * @api
 */
Projection.prototype.setGetPointResolution = function setGetPointResolution (func) {
  this.getPointResolutionFunc_ = func;
};

/**
 * Get the custom point resolution function for this projection (if set).
 * @return {function(number, import("../coordinate.js").Coordinate):number|undefined} The custom point
 * resolution function (if set).
 */
Projection.prototype.getPointResolutionFunc = function getPointResolutionFunc () {
  return this.getPointResolutionFunc_;
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Projection);

//# sourceMappingURL=Projection.js.map

/***/ }),

/***/ "./node_modules/ol/proj/Units.js":
/*!***************************************!*\
  !*** ./node_modules/ol/proj/Units.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "METERS_PER_UNIT": () => (/* binding */ METERS_PER_UNIT),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/proj/Units
 */

/**
 * Projection units: `'degrees'`, `'ft'`, `'m'`, `'pixels'`, `'tile-pixels'` or
 * `'us-ft'`.
 * @enum {string}
 */
var Units = {
  DEGREES: 'degrees',
  FEET: 'ft',
  METERS: 'm',
  PIXELS: 'pixels',
  TILE_PIXELS: 'tile-pixels',
  USFEET: 'us-ft'
};


/**
 * Meters per unit lookup table.
 * @const
 * @type {Object<Units, number>}
 * @api
 */
var METERS_PER_UNIT = {};
// use the radius of the Normal sphere
METERS_PER_UNIT[Units.DEGREES] = 2 * Math.PI * 6370997 / 360;
METERS_PER_UNIT[Units.FEET] = 0.3048;
METERS_PER_UNIT[Units.METERS] = 1;
METERS_PER_UNIT[Units.USFEET] = 1200 / 3937;

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Units);

//# sourceMappingURL=Units.js.map

/***/ }),

/***/ "./node_modules/ol/proj/epsg3857.js":
/*!******************************************!*\
  !*** ./node_modules/ol/proj/epsg3857.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "RADIUS": () => (/* binding */ RADIUS),
/* harmony export */   "HALF_SIZE": () => (/* binding */ HALF_SIZE),
/* harmony export */   "EXTENT": () => (/* binding */ EXTENT),
/* harmony export */   "WORLD_EXTENT": () => (/* binding */ WORLD_EXTENT),
/* harmony export */   "PROJECTIONS": () => (/* binding */ PROJECTIONS),
/* harmony export */   "fromEPSG4326": () => (/* binding */ fromEPSG4326),
/* harmony export */   "toEPSG4326": () => (/* binding */ toEPSG4326)
/* harmony export */ });
/* harmony import */ var _math_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../math.js */ "./node_modules/ol/math.js");
/* harmony import */ var _Projection_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Projection.js */ "./node_modules/ol/proj/Projection.js");
/* harmony import */ var _Units_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Units.js */ "./node_modules/ol/proj/Units.js");
/**
 * @module ol/proj/epsg3857
 */





/**
 * Radius of WGS84 sphere
 *
 * @const
 * @type {number}
 */
var RADIUS = 6378137;


/**
 * @const
 * @type {number}
 */
var HALF_SIZE = Math.PI * RADIUS;


/**
 * @const
 * @type {import("../extent.js").Extent}
 */
var EXTENT = [
  -HALF_SIZE, -HALF_SIZE,
  HALF_SIZE, HALF_SIZE
];


/**
 * @const
 * @type {import("../extent.js").Extent}
 */
var WORLD_EXTENT = [-180, -85, 180, 85];


/**
 * @classdesc
 * Projection object for web/spherical Mercator (EPSG:3857).
 */
var EPSG3857Projection = /*@__PURE__*/(function (Projection) {
  function EPSG3857Projection(code) {
    Projection.call(this, {
      code: code,
      units: _Units_js__WEBPACK_IMPORTED_MODULE_0__["default"].METERS,
      extent: EXTENT,
      global: true,
      worldExtent: WORLD_EXTENT,
      getPointResolution: function(resolution, point) {
        return resolution / (0,_math_js__WEBPACK_IMPORTED_MODULE_1__.cosh)(point[1] / RADIUS);
      }
    });

  }

  if ( Projection ) EPSG3857Projection.__proto__ = Projection;
  EPSG3857Projection.prototype = Object.create( Projection && Projection.prototype );
  EPSG3857Projection.prototype.constructor = EPSG3857Projection;

  return EPSG3857Projection;
}(_Projection_js__WEBPACK_IMPORTED_MODULE_2__["default"]));


/**
 * Projections equal to EPSG:3857.
 *
 * @const
 * @type {Array<import("./Projection.js").default>}
 */
var PROJECTIONS = [
  new EPSG3857Projection('EPSG:3857'),
  new EPSG3857Projection('EPSG:102100'),
  new EPSG3857Projection('EPSG:102113'),
  new EPSG3857Projection('EPSG:900913'),
  new EPSG3857Projection('urn:ogc:def:crs:EPSG:6.18:3:3857'),
  new EPSG3857Projection('urn:ogc:def:crs:EPSG::3857'),
  new EPSG3857Projection('http://www.opengis.net/gml/srs/epsg.xml#3857')
];


/**
 * Transformation from EPSG:4326 to EPSG:3857.
 *
 * @param {Array<number>} input Input array of coordinate values.
 * @param {Array<number>=} opt_output Output array of coordinate values.
 * @param {number=} opt_dimension Dimension (default is `2`).
 * @return {Array<number>} Output array of coordinate values.
 */
function fromEPSG4326(input, opt_output, opt_dimension) {
  var length = input.length;
  var dimension = opt_dimension > 1 ? opt_dimension : 2;
  var output = opt_output;
  if (output === undefined) {
    if (dimension > 2) {
      // preserve values beyond second dimension
      output = input.slice();
    } else {
      output = new Array(length);
    }
  }
  var halfSize = HALF_SIZE;
  for (var i = 0; i < length; i += dimension) {
    output[i] = halfSize * input[i] / 180;
    var y = RADIUS *
        Math.log(Math.tan(Math.PI * (input[i + 1] + 90) / 360));
    if (y > halfSize) {
      y = halfSize;
    } else if (y < -halfSize) {
      y = -halfSize;
    }
    output[i + 1] = y;
  }
  return output;
}


/**
 * Transformation from EPSG:3857 to EPSG:4326.
 *
 * @param {Array<number>} input Input array of coordinate values.
 * @param {Array<number>=} opt_output Output array of coordinate values.
 * @param {number=} opt_dimension Dimension (default is `2`).
 * @return {Array<number>} Output array of coordinate values.
 */
function toEPSG4326(input, opt_output, opt_dimension) {
  var length = input.length;
  var dimension = opt_dimension > 1 ? opt_dimension : 2;
  var output = opt_output;
  if (output === undefined) {
    if (dimension > 2) {
      // preserve values beyond second dimension
      output = input.slice();
    } else {
      output = new Array(length);
    }
  }
  for (var i = 0; i < length; i += dimension) {
    output[i] = 180 * input[i] / HALF_SIZE;
    output[i + 1] = 360 * Math.atan(
      Math.exp(input[i + 1] / RADIUS)) / Math.PI - 90;
  }
  return output;
}

//# sourceMappingURL=epsg3857.js.map

/***/ }),

/***/ "./node_modules/ol/proj/epsg4326.js":
/*!******************************************!*\
  !*** ./node_modules/ol/proj/epsg4326.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "RADIUS": () => (/* binding */ RADIUS),
/* harmony export */   "EXTENT": () => (/* binding */ EXTENT),
/* harmony export */   "METERS_PER_UNIT": () => (/* binding */ METERS_PER_UNIT),
/* harmony export */   "PROJECTIONS": () => (/* binding */ PROJECTIONS)
/* harmony export */ });
/* harmony import */ var _Projection_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Projection.js */ "./node_modules/ol/proj/Projection.js");
/* harmony import */ var _Units_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Units.js */ "./node_modules/ol/proj/Units.js");
/**
 * @module ol/proj/epsg4326
 */




/**
 * Semi-major radius of the WGS84 ellipsoid.
 *
 * @const
 * @type {number}
 */
var RADIUS = 6378137;


/**
 * Extent of the EPSG:4326 projection which is the whole world.
 *
 * @const
 * @type {import("../extent.js").Extent}
 */
var EXTENT = [-180, -90, 180, 90];


/**
 * @const
 * @type {number}
 */
var METERS_PER_UNIT = Math.PI * RADIUS / 180;


/**
 * @classdesc
 * Projection object for WGS84 geographic coordinates (EPSG:4326).
 *
 * Note that OpenLayers does not strictly comply with the EPSG definition.
 * The EPSG registry defines 4326 as a CRS for Latitude,Longitude (y,x).
 * OpenLayers treats EPSG:4326 as a pseudo-projection, with x,y coordinates.
 */
var EPSG4326Projection = /*@__PURE__*/(function (Projection) {
  function EPSG4326Projection(code, opt_axisOrientation) {
    Projection.call(this, {
      code: code,
      units: _Units_js__WEBPACK_IMPORTED_MODULE_0__["default"].DEGREES,
      extent: EXTENT,
      axisOrientation: opt_axisOrientation,
      global: true,
      metersPerUnit: METERS_PER_UNIT,
      worldExtent: EXTENT
    });

  }

  if ( Projection ) EPSG4326Projection.__proto__ = Projection;
  EPSG4326Projection.prototype = Object.create( Projection && Projection.prototype );
  EPSG4326Projection.prototype.constructor = EPSG4326Projection;

  return EPSG4326Projection;
}(_Projection_js__WEBPACK_IMPORTED_MODULE_1__["default"]));


/**
 * Projections equal to EPSG:4326.
 *
 * @const
 * @type {Array<import("./Projection.js").default>}
 */
var PROJECTIONS = [
  new EPSG4326Projection('CRS:84'),
  new EPSG4326Projection('EPSG:4326', 'neu'),
  new EPSG4326Projection('urn:ogc:def:crs:EPSG::4326', 'neu'),
  new EPSG4326Projection('urn:ogc:def:crs:EPSG:6.6:4326', 'neu'),
  new EPSG4326Projection('urn:ogc:def:crs:OGC:1.3:CRS84'),
  new EPSG4326Projection('urn:ogc:def:crs:OGC:2:84'),
  new EPSG4326Projection('http://www.opengis.net/gml/srs/epsg.xml#4326', 'neu'),
  new EPSG4326Projection('urn:x-ogc:def:crs:EPSG:4326', 'neu')
];

//# sourceMappingURL=epsg4326.js.map

/***/ }),

/***/ "./node_modules/ol/proj/projections.js":
/*!*********************************************!*\
  !*** ./node_modules/ol/proj/projections.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "clear": () => (/* binding */ clear),
/* harmony export */   "get": () => (/* binding */ get),
/* harmony export */   "add": () => (/* binding */ add)
/* harmony export */ });
/**
 * @module ol/proj/projections
 */


/**
 * @type {Object<string, import("./Projection.js").default>}
 */
var cache = {};


/**
 * Clear the projections cache.
 */
function clear() {
  cache = {};
}


/**
 * Get a cached projection by code.
 * @param {string} code The code for the projection.
 * @return {import("./Projection.js").default} The projection (if cached).
 */
function get(code) {
  return cache[code] || null;
}


/**
 * Add a projection to the cache.
 * @param {string} code The projection code.
 * @param {import("./Projection.js").default} projection The projection to cache.
 */
function add(code, projection) {
  cache[code] = projection;
}

//# sourceMappingURL=projections.js.map

/***/ }),

/***/ "./node_modules/ol/proj/transforms.js":
/*!********************************************!*\
  !*** ./node_modules/ol/proj/transforms.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "clear": () => (/* binding */ clear),
/* harmony export */   "add": () => (/* binding */ add),
/* harmony export */   "remove": () => (/* binding */ remove),
/* harmony export */   "get": () => (/* binding */ get)
/* harmony export */ });
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../obj.js */ "./node_modules/ol/obj.js");
/**
 * @module ol/proj/transforms
 */



/**
 * @private
 * @type {!Object<string, Object<string, import("../proj.js").TransformFunction>>}
 */
var transforms = {};


/**
 * Clear the transform cache.
 */
function clear() {
  transforms = {};
}


/**
 * Registers a conversion function to convert coordinates from the source
 * projection to the destination projection.
 *
 * @param {import("./Projection.js").default} source Source.
 * @param {import("./Projection.js").default} destination Destination.
 * @param {import("../proj.js").TransformFunction} transformFn Transform.
 */
function add(source, destination, transformFn) {
  var sourceCode = source.getCode();
  var destinationCode = destination.getCode();
  if (!(sourceCode in transforms)) {
    transforms[sourceCode] = {};
  }
  transforms[sourceCode][destinationCode] = transformFn;
}


/**
 * Unregisters the conversion function to convert coordinates from the source
 * projection to the destination projection.  This method is used to clean up
 * cached transforms during testing.
 *
 * @param {import("./Projection.js").default} source Source projection.
 * @param {import("./Projection.js").default} destination Destination projection.
 * @return {import("../proj.js").TransformFunction} transformFn The unregistered transform.
 */
function remove(source, destination) {
  var sourceCode = source.getCode();
  var destinationCode = destination.getCode();
  var transform = transforms[sourceCode][destinationCode];
  delete transforms[sourceCode][destinationCode];
  if ((0,_obj_js__WEBPACK_IMPORTED_MODULE_0__.isEmpty)(transforms[sourceCode])) {
    delete transforms[sourceCode];
  }
  return transform;
}


/**
 * Get a transform given a source code and a destination code.
 * @param {string} sourceCode The code for the source projection.
 * @param {string} destinationCode The code for the destination projection.
 * @return {import("../proj.js").TransformFunction|undefined} The transform function (if found).
 */
function get(sourceCode, destinationCode) {
  var transform;
  if (sourceCode in transforms && destinationCode in transforms[sourceCode]) {
    transform = transforms[sourceCode][destinationCode];
  }
  return transform;
}

//# sourceMappingURL=transforms.js.map

/***/ }),

/***/ "./node_modules/ol/render/EventType.js":
/*!*********************************************!*\
  !*** ./node_modules/ol/render/EventType.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/render/EventType
 */

/**
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  /**
   * @event module:ol/render/Event~RenderEvent#postcompose
   * @api
   */
  POSTCOMPOSE: 'postcompose',
  /**
   * @event module:ol/render/Event~RenderEvent#precompose
   * @api
   */
  PRECOMPOSE: 'precompose',
  /**
   * @event module:ol/render/Event~RenderEvent#render
   * @api
   */
  RENDER: 'render',
  /**
   * Triggered when rendering is complete, i.e. all sources and tiles have
   * finished loading for the current viewport, and all tiles are faded in.
   * @event module:ol/render/Event~RenderEvent#rendercomplete
   * @api
   */
  RENDERCOMPLETE: 'rendercomplete'
});

//# sourceMappingURL=EventType.js.map

/***/ }),

/***/ "./node_modules/ol/render/canvas.js":
/*!******************************************!*\
  !*** ./node_modules/ol/render/canvas.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "defaultFont": () => (/* binding */ defaultFont),
/* harmony export */   "defaultFillStyle": () => (/* binding */ defaultFillStyle),
/* harmony export */   "defaultLineCap": () => (/* binding */ defaultLineCap),
/* harmony export */   "defaultLineDash": () => (/* binding */ defaultLineDash),
/* harmony export */   "defaultLineDashOffset": () => (/* binding */ defaultLineDashOffset),
/* harmony export */   "defaultLineJoin": () => (/* binding */ defaultLineJoin),
/* harmony export */   "defaultMiterLimit": () => (/* binding */ defaultMiterLimit),
/* harmony export */   "defaultStrokeStyle": () => (/* binding */ defaultStrokeStyle),
/* harmony export */   "defaultTextAlign": () => (/* binding */ defaultTextAlign),
/* harmony export */   "defaultTextBaseline": () => (/* binding */ defaultTextBaseline),
/* harmony export */   "defaultPadding": () => (/* binding */ defaultPadding),
/* harmony export */   "defaultLineWidth": () => (/* binding */ defaultLineWidth),
/* harmony export */   "labelCache": () => (/* binding */ labelCache),
/* harmony export */   "checkedFonts": () => (/* binding */ checkedFonts),
/* harmony export */   "textHeights": () => (/* binding */ textHeights),
/* harmony export */   "checkFont": () => (/* binding */ checkFont),
/* harmony export */   "measureTextHeight": () => (/* binding */ measureTextHeight),
/* harmony export */   "measureTextWidth": () => (/* binding */ measureTextWidth),
/* harmony export */   "rotateAtOffset": () => (/* binding */ rotateAtOffset),
/* harmony export */   "resetTransform": () => (/* binding */ resetTransform),
/* harmony export */   "drawImage": () => (/* binding */ drawImage)
/* harmony export */ });
/* harmony import */ var _css_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../css.js */ "./node_modules/ol/css.js");
/* harmony import */ var _dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../dom.js */ "./node_modules/ol/dom.js");
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../obj.js */ "./node_modules/ol/obj.js");
/* harmony import */ var _structs_LRUCache_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../structs/LRUCache.js */ "./node_modules/ol/structs/LRUCache.js");
/* harmony import */ var _transform_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../transform.js */ "./node_modules/ol/transform.js");
/**
 * @module ol/render/canvas
 */







/**
 * @typedef {Object} FillState
 * @property {import("../colorlike.js").ColorLike} fillStyle
 */


/**
 * @typedef {Object} FillStrokeState
 * @property {import("../colorlike.js").ColorLike} [currentFillStyle]
 * @property {import("../colorlike.js").ColorLike} [currentStrokeStyle]
 * @property {string} [currentLineCap]
 * @property {Array<number>} currentLineDash
 * @property {number} [currentLineDashOffset]
 * @property {string} [currentLineJoin]
 * @property {number} [currentLineWidth]
 * @property {number} [currentMiterLimit]
 * @property {number} [lastStroke]
 * @property {import("../colorlike.js").ColorLike} [fillStyle]
 * @property {import("../colorlike.js").ColorLike} [strokeStyle]
 * @property {string} [lineCap]
 * @property {Array<number>} lineDash
 * @property {number} [lineDashOffset]
 * @property {string} [lineJoin]
 * @property {number} [lineWidth]
 * @property {number} [miterLimit]
 */


/**
 * @typedef {Object} StrokeState
 * @property {string} lineCap
 * @property {Array<number>} lineDash
 * @property {number} lineDashOffset
 * @property {string} lineJoin
 * @property {number} lineWidth
 * @property {number} miterLimit
 * @property {import("../colorlike.js").ColorLike} strokeStyle
 */


/**
 * @typedef {Object} TextState
 * @property {string} font
 * @property {string} [textAlign]
 * @property {string} textBaseline
 * @property {string} [placement]
 * @property {number} [maxAngle]
 * @property {boolean} [overflow]
 * @property {import("../style/Fill.js").default} [backgroundFill]
 * @property {import("../style/Stroke.js").default} [backgroundStroke]
 * @property {number} [scale]
 * @property {Array<number>} [padding]
 */


/**
 * Container for decluttered replay instructions that need to be rendered or
 * omitted together, i.e. when styles render both an image and text, or for the
 * characters that form text along lines. The basic elements of this array are
 * `[minX, minY, maxX, maxY, count]`, where the first four entries are the
 * rendered extent of the group in pixel space. `count` is the number of styles
 * in the group, i.e. 2 when an image and a text are grouped, or 1 otherwise.
 * In addition to these four elements, declutter instruction arrays (i.e. the
 * arguments to {@link module:ol/render/canvas~drawImage} are appended to the array.
 * @typedef {Array<*>} DeclutterGroup
 */


/**
 * @const
 * @type {string}
 */
var defaultFont = '10px sans-serif';


/**
 * @const
 * @type {import("../color.js").Color}
 */
var defaultFillStyle = [0, 0, 0, 1];


/**
 * @const
 * @type {string}
 */
var defaultLineCap = 'round';


/**
 * @const
 * @type {Array<number>}
 */
var defaultLineDash = [];


/**
 * @const
 * @type {number}
 */
var defaultLineDashOffset = 0;


/**
 * @const
 * @type {string}
 */
var defaultLineJoin = 'round';


/**
 * @const
 * @type {number}
 */
var defaultMiterLimit = 10;


/**
 * @const
 * @type {import("../color.js").Color}
 */
var defaultStrokeStyle = [0, 0, 0, 1];


/**
 * @const
 * @type {string}
 */
var defaultTextAlign = 'center';


/**
 * @const
 * @type {string}
 */
var defaultTextBaseline = 'middle';


/**
 * @const
 * @type {Array<number>}
 */
var defaultPadding = [0, 0, 0, 0];


/**
 * @const
 * @type {number}
 */
var defaultLineWidth = 1;


/**
 * The label cache for text rendering. To change the default cache size of 2048
 * entries, use {@link module:ol/structs/LRUCache#setSize}.
 * @type {LRUCache<HTMLCanvasElement>}
 * @api
 */
var labelCache = new _structs_LRUCache_js__WEBPACK_IMPORTED_MODULE_0__["default"]();


/**
 * @type {!Object<string, number>}
 */
var checkedFonts = {};


/**
 * @type {CanvasRenderingContext2D}
 */
var measureContext = null;


/**
 * @type {!Object<string, number>}
 */
var textHeights = {};


/**
 * Clears the label cache when a font becomes available.
 * @param {string} fontSpec CSS font spec.
 */
var checkFont = (function() {
  var retries = 60;
  var checked = checkedFonts;
  var size = '32px ';
  var referenceFonts = ['monospace', 'serif'];
  var len = referenceFonts.length;
  var text = 'wmytzilWMYTZIL@#/&?$%10\uF013';
  var interval, referenceWidth;

  function isAvailable(font) {
    var context = getMeasureContext();
    // Check weight ranges according to
    // https://developer.mozilla.org/en-US/docs/Web/CSS/font-weight#Fallback_weights
    for (var weight = 100; weight <= 700; weight += 300) {
      var fontWeight = weight + ' ';
      var available = true;
      for (var i = 0; i < len; ++i) {
        var referenceFont = referenceFonts[i];
        context.font = fontWeight + size + referenceFont;
        referenceWidth = context.measureText(text).width;
        if (font != referenceFont) {
          context.font = fontWeight + size + font + ',' + referenceFont;
          var width = context.measureText(text).width;
          // If width and referenceWidth are the same, then the fallback was used
          // instead of the font we wanted, so the font is not available.
          available = available && width != referenceWidth;
        }
      }
      if (available) {
        // Consider font available when it is available in one weight range.
        //FIXME With this we miss rare corner cases, so we should consider
        //FIXME checking availability for each requested weight range.
        return true;
      }
    }
    return false;
  }

  function check() {
    var done = true;
    for (var font in checked) {
      if (checked[font] < retries) {
        if (isAvailable(font)) {
          checked[font] = retries;
          (0,_obj_js__WEBPACK_IMPORTED_MODULE_1__.clear)(textHeights);
          // Make sure that loaded fonts are picked up by Safari
          measureContext = null;
          labelCache.clear();
        } else {
          ++checked[font];
          done = false;
        }
      }
    }
    if (done) {
      clearInterval(interval);
      interval = undefined;
    }
  }

  return function(fontSpec) {
    var fontFamilies = (0,_css_js__WEBPACK_IMPORTED_MODULE_2__.getFontFamilies)(fontSpec);
    if (!fontFamilies) {
      return;
    }
    for (var i = 0, ii = fontFamilies.length; i < ii; ++i) {
      var fontFamily = fontFamilies[i];
      if (!(fontFamily in checked)) {
        checked[fontFamily] = retries;
        if (!isAvailable(fontFamily)) {
          checked[fontFamily] = 0;
          if (interval === undefined) {
            interval = setInterval(check, 32);
          }
        }
      }
    }
  };
})();


/**
 * @return {CanvasRenderingContext2D} Measure context.
 */
function getMeasureContext() {
  if (!measureContext) {
    measureContext = (0,_dom_js__WEBPACK_IMPORTED_MODULE_3__.createCanvasContext2D)(1, 1);
  }
  return measureContext;
}


/**
 * @param {string} font Font to use for measuring.
 * @return {import("../size.js").Size} Measurement.
 */
var measureTextHeight = (function() {
  var span;
  var heights = textHeights;
  return function(font) {
    var height = heights[font];
    if (height == undefined) {
      if (!span) {
        span = document.createElement('span');
        span.textContent = 'M';
        span.style.margin = span.style.padding = '0 !important';
        span.style.position = 'absolute !important';
        span.style.left = '-99999px !important';
      }
      span.style.font = font;
      document.body.appendChild(span);
      height = heights[font] = span.offsetHeight;
      document.body.removeChild(span);
    }
    return height;
  };
})();


/**
 * @param {string} font Font.
 * @param {string} text Text.
 * @return {number} Width.
 */
function measureTextWidth(font, text) {
  var measureContext = getMeasureContext();
  if (font != measureContext.font) {
    measureContext.font = font;
  }
  return measureContext.measureText(text).width;
}


/**
 * @param {CanvasRenderingContext2D} context Context.
 * @param {number} rotation Rotation.
 * @param {number} offsetX X offset.
 * @param {number} offsetY Y offset.
 */
function rotateAtOffset(context, rotation, offsetX, offsetY) {
  if (rotation !== 0) {
    context.translate(offsetX, offsetY);
    context.rotate(rotation);
    context.translate(-offsetX, -offsetY);
  }
}


var resetTransform = (0,_transform_js__WEBPACK_IMPORTED_MODULE_4__.create)();


/**
 * @param {CanvasRenderingContext2D} context Context.
 * @param {import("../transform.js").Transform|null} transform Transform.
 * @param {number} opacity Opacity.
 * @param {HTMLImageElement|HTMLCanvasElement|HTMLVideoElement} image Image.
 * @param {number} originX Origin X.
 * @param {number} originY Origin Y.
 * @param {number} w Width.
 * @param {number} h Height.
 * @param {number} x X.
 * @param {number} y Y.
 * @param {number} scale Scale.
 */
function drawImage(context,
  transform, opacity, image, originX, originY, w, h, x, y, scale) {
  var alpha;
  if (opacity != 1) {
    alpha = context.globalAlpha;
    context.globalAlpha = alpha * opacity;
  }
  if (transform) {
    context.setTransform.apply(context, transform);
  }

  context.drawImage(image, originX, originY, w, h, x, y, w * scale, h * scale);

  if (alpha) {
    context.globalAlpha = alpha;
  }
  if (transform) {
    context.setTransform.apply(context, resetTransform);
  }
}

//# sourceMappingURL=canvas.js.map

/***/ }),

/***/ "./node_modules/ol/source/Source.js":
/*!******************************************!*\
  !*** ./node_modules/ol/source/Source.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../util.js */ "./node_modules/ol/util.js");
/* harmony import */ var _Object_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Object.js */ "./node_modules/ol/Object.js");
/* harmony import */ var _proj_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../proj.js */ "./node_modules/ol/proj.js");
/* harmony import */ var _State_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./State.js */ "./node_modules/ol/source/State.js");
/**
 * @module ol/source/Source
 */






/**
 * A function that returns a string or an array of strings representing source
 * attributions.
 *
 * @typedef {function(import("../PluggableMap.js").FrameState): (string|Array<string>)} Attribution
 */


/**
 * A type that can be used to provide attribution information for data sources.
 *
 * It represents either
 * * a simple string (e.g. `'© Acme Inc.'`)
 * * an array of simple strings (e.g. `['© Acme Inc.', '© Bacme Inc.']`)
 * * a function that returns a string or array of strings (`{@link module:ol/source/Source~Attribution}`)
 *
 * @typedef {string|Array<string>|Attribution} AttributionLike
 */


/**
 * @typedef {Object} Options
 * @property {AttributionLike} [attributions]
 * @property {boolean} [attributionsCollapsible=true] Attributions are collapsible.
 * @property {import("../proj.js").ProjectionLike} projection
 * @property {SourceState} [state='ready']
 * @property {boolean} [wrapX=false]
 */


/**
 * @classdesc
 * Abstract base class; normally only used for creating subclasses and not
 * instantiated in apps.
 * Base class for {@link module:ol/layer/Layer~Layer} sources.
 *
 * A generic `change` event is triggered when the state of the source changes.
 * @abstract
 * @api
 */
var Source = /*@__PURE__*/(function (BaseObject) {
  function Source(options) {

    BaseObject.call(this);

    /**
     * @private
     * @type {import("../proj/Projection.js").default}
     */
    this.projection_ = (0,_proj_js__WEBPACK_IMPORTED_MODULE_0__.get)(options.projection);

    /**
     * @private
     * @type {?Attribution}
     */
    this.attributions_ = adaptAttributions(options.attributions);

    /**
     * @private
     * @type {boolean}
     */
    this.attributionsCollapsible_ = options.attributionsCollapsible !== undefined ?
      options.attributionsCollapsible : true;

    /**
     * This source is currently loading data. Sources that defer loading to the
     * map's tile queue never set this to `true`.
     * @type {boolean}
     */
    this.loading = false;

    /**
     * @private
     * @type {SourceState}
     */
    this.state_ = options.state !== undefined ?
      options.state : _State_js__WEBPACK_IMPORTED_MODULE_1__["default"].READY;

    /**
     * @private
     * @type {boolean}
     */
    this.wrapX_ = options.wrapX !== undefined ? options.wrapX : false;

  }

  if ( BaseObject ) Source.__proto__ = BaseObject;
  Source.prototype = Object.create( BaseObject && BaseObject.prototype );
  Source.prototype.constructor = Source;

  /**
   * Get the attribution function for the source.
   * @return {?Attribution} Attribution function.
   */
  Source.prototype.getAttributions = function getAttributions () {
    return this.attributions_;
  };

  /**
   * @return {boolean} Aattributions are collapsible.
   */
  Source.prototype.getAttributionsCollapsible = function getAttributionsCollapsible () {
    return this.attributionsCollapsible_;
  };

  /**
   * Get the projection of the source.
   * @return {import("../proj/Projection.js").default} Projection.
   * @api
   */
  Source.prototype.getProjection = function getProjection () {
    return this.projection_;
  };

  /**
   * @abstract
   * @return {Array<number>|undefined} Resolutions.
   */
  Source.prototype.getResolutions = function getResolutions () {
    return (0,_util_js__WEBPACK_IMPORTED_MODULE_2__.abstract)();
  };

  /**
   * Get the state of the source, see {@link module:ol/source/State~State} for possible states.
   * @return {SourceState} State.
   * @api
   */
  Source.prototype.getState = function getState () {
    return this.state_;
  };

  /**
   * @return {boolean|undefined} Wrap X.
   */
  Source.prototype.getWrapX = function getWrapX () {
    return this.wrapX_;
  };

  /**
   * Refreshes the source and finally dispatches a 'change' event.
   * @api
   */
  Source.prototype.refresh = function refresh () {
    this.changed();
  };

  /**
   * Set the attributions of the source.
   * @param {AttributionLike|undefined} attributions Attributions.
   *     Can be passed as `string`, `Array<string>`, `{@link module:ol/source/Source~Attribution}`,
   *     or `undefined`.
   * @api
   */
  Source.prototype.setAttributions = function setAttributions (attributions) {
    this.attributions_ = adaptAttributions(attributions);
    this.changed();
  };

  /**
   * Set the state of the source.
   * @param {SourceState} state State.
   * @protected
   */
  Source.prototype.setState = function setState (state) {
    this.state_ = state;
    this.changed();
  };

  return Source;
}(_Object_js__WEBPACK_IMPORTED_MODULE_3__["default"]));


/**
 * Turns the attributions option into an attributions function.
 * @param {AttributionLike|undefined} attributionLike The attribution option.
 * @return {?Attribution} An attribution function (or null).
 */
function adaptAttributions(attributionLike) {
  if (!attributionLike) {
    return null;
  }
  if (Array.isArray(attributionLike)) {
    return function(frameState) {
      return attributionLike;
    };
  }

  if (typeof attributionLike === 'function') {
    return attributionLike;
  }

  return function(frameState) {
    return [attributionLike];
  };
}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Source);

//# sourceMappingURL=Source.js.map

/***/ }),

/***/ "./node_modules/ol/source/State.js":
/*!*****************************************!*\
  !*** ./node_modules/ol/source/State.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/source/State
 */

/**
 * @enum {string}
 * State of the source, one of 'undefined', 'loading', 'ready' or 'error'.
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  UNDEFINED: 'undefined',
  LOADING: 'loading',
  READY: 'ready',
  ERROR: 'error'
});

//# sourceMappingURL=State.js.map

/***/ }),

/***/ "./node_modules/ol/source/Vector.js":
/*!******************************************!*\
  !*** ./node_modules/ol/source/Vector.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "VectorSourceEvent": () => (/* binding */ VectorSourceEvent),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../util.js */ "./node_modules/ol/util.js");
/* harmony import */ var _Collection_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../Collection.js */ "./node_modules/ol/Collection.js");
/* harmony import */ var _CollectionEventType_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../CollectionEventType.js */ "./node_modules/ol/CollectionEventType.js");
/* harmony import */ var _ObjectEventType_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../ObjectEventType.js */ "./node_modules/ol/ObjectEventType.js");
/* harmony import */ var _array_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../array.js */ "./node_modules/ol/array.js");
/* harmony import */ var _asserts_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../asserts.js */ "./node_modules/ol/asserts.js");
/* harmony import */ var _events_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../events.js */ "./node_modules/ol/events.js");
/* harmony import */ var _events_Event_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../events/Event.js */ "./node_modules/ol/events/Event.js");
/* harmony import */ var _events_EventType_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../events/EventType.js */ "./node_modules/ol/events/EventType.js");
/* harmony import */ var _extent_js__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ../extent.js */ "./node_modules/ol/extent.js");
/* harmony import */ var _featureloader_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../featureloader.js */ "./node_modules/ol/featureloader.js");
/* harmony import */ var _functions_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../functions.js */ "./node_modules/ol/functions.js");
/* harmony import */ var _loadingstrategy_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../loadingstrategy.js */ "./node_modules/ol/loadingstrategy.js");
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../obj.js */ "./node_modules/ol/obj.js");
/* harmony import */ var _Source_js__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./Source.js */ "./node_modules/ol/source/Source.js");
/* harmony import */ var _State_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./State.js */ "./node_modules/ol/source/State.js");
/* harmony import */ var _VectorEventType_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./VectorEventType.js */ "./node_modules/ol/source/VectorEventType.js");
/* harmony import */ var _structs_RBush_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../structs/RBush.js */ "./node_modules/ol/structs/RBush.js");
/**
 * @module ol/source/Vector
 */




















/**
 * A function that takes an {@link module:ol/extent~Extent} and a resolution as arguments, and
 * returns an array of {@link module:ol/extent~Extent} with the extents to load. Usually this
 * is one of the standard {@link module:ol/loadingstrategy} strategies.
 *
 * @typedef {function(import("../extent.js").Extent, number): Array<import("../extent.js").Extent>} LoadingStrategy
 * @api
 */


/**
 * @classdesc
 * Events emitted by {@link module:ol/source/Vector} instances are instances of this
 * type.
 */
var VectorSourceEvent = /*@__PURE__*/(function (Event) {
  function VectorSourceEvent(type, opt_feature) {

    Event.call(this, type);

    /**
     * The feature being added or removed.
     * @type {import("../Feature.js").default|undefined}
     * @api
     */
    this.feature = opt_feature;

  }

  if ( Event ) VectorSourceEvent.__proto__ = Event;
  VectorSourceEvent.prototype = Object.create( Event && Event.prototype );
  VectorSourceEvent.prototype.constructor = VectorSourceEvent;

  return VectorSourceEvent;
}(_events_Event_js__WEBPACK_IMPORTED_MODULE_0__["default"]));


/**
 * @typedef {Object} Options
 * @property {import("./Source.js").AttributionLike} [attributions] Attributions.
 * @property {Array<import("../Feature.js").default>|Collection<import("../Feature.js").default>} [features]
 * Features. If provided as {@link module:ol/Collection}, the features in the source
 * and the collection will stay in sync.
 * @property {import("../format/Feature.js").default} [format] The feature format used by the XHR
 * feature loader when `url` is set. Required if `url` is set, otherwise ignored.
 * @property {import("../featureloader.js").FeatureLoader} [loader]
 * The loader function used to load features, from a remote source for example.
 * If this is not set and `url` is set, the source will create and use an XHR
 * feature loader.
 *
 * Example:
 *
 * ```js
 * import {Vector} from 'ol/source';
 * import {GeoJSON} from 'ol/format';
 * import {bbox} from 'ol/loadingstrategy';
 *
 * var vectorSource = new Vector({
 *   format: new GeoJSON(),
 *   loader: function(extent, resolution, projection) {
 *      var proj = projection.getCode();
 *      var url = 'https://ahocevar.com/geoserver/wfs?service=WFS&' +
 *          'version=1.1.0&request=GetFeature&typename=osm:water_areas&' +
 *          'outputFormat=application/json&srsname=' + proj + '&' +
 *          'bbox=' + extent.join(',') + ',' + proj;
 *      var xhr = new XMLHttpRequest();
 *      xhr.open('GET', url);
 *      var onError = function() {
 *        vectorSource.removeLoadedExtent(extent);
 *      }
 *      xhr.onerror = onError;
 *      xhr.onload = function() {
 *        if (xhr.status == 200) {
 *          vectorSource.addFeatures(
 *              vectorSource.getFormat().readFeatures(xhr.responseText));
 *        } else {
 *          onError();
 *        }
 *      }
 *      xhr.send();
 *    },
 *    strategy: bbox
 *  });
 * ```
 * @property {boolean} [overlaps=true] This source may have overlapping geometries.
 * Setting this to `false` (e.g. for sources with polygons that represent administrative
 * boundaries or TopoJSON sources) allows the renderer to optimise fill and
 * stroke operations.
 * @property {LoadingStrategy} [strategy] The loading strategy to use.
 * By default an {@link module:ol/loadingstrategy~all}
 * strategy is used, a one-off strategy which loads all features at once.
 * @property {string|import("../featureloader.js").FeatureUrlFunction} [url]
 * Setting this option instructs the source to load features using an XHR loader
 * (see {@link module:ol/featureloader~xhr}). Use a `string` and an
 * {@link module:ol/loadingstrategy~all} for a one-off download of all features from
 * the given URL. Use a {@link module:ol/featureloader~FeatureUrlFunction} to generate the url with
 * other loading strategies.
 * Requires `format` to be set as well.
 * When default XHR feature loader is provided, the features will
 * be transformed from the data projection to the view projection
 * during parsing. If your remote data source does not advertise its projection
 * properly, this transformation will be incorrect. For some formats, the
 * default projection (usually EPSG:4326) can be overridden by setting the
 * dataProjection constructor option on the format.
 * Note that if a source contains non-feature data, such as a GeoJSON geometry
 * or a KML NetworkLink, these will be ignored. Use a custom loader to load these.
 * @property {boolean} [useSpatialIndex=true]
 * By default, an RTree is used as spatial index. When features are removed and
 * added frequently, and the total number of features is low, setting this to
 * `false` may improve performance.
 *
 * Note that
 * {@link module:ol/source/Vector~VectorSource#getFeaturesInExtent},
 * {@link module:ol/source/Vector~VectorSource#getClosestFeatureToCoordinate} and
 * {@link module:ol/source/Vector~VectorSource#getExtent} cannot be used when `useSpatialIndex` is
 * set to `false`, and {@link module:ol/source/Vector~VectorSource#forEachFeatureInExtent} will loop
 * through all features.
 *
 * When set to `false`, the features will be maintained in an
 * {@link module:ol/Collection}, which can be retrieved through
 * {@link module:ol/source/Vector~VectorSource#getFeaturesCollection}.
 * @property {boolean} [wrapX=true] Wrap the world horizontally. For vector editing across the
 * -180° and 180° meridians to work properly, this should be set to `false`. The
 * resulting geometry coordinates will then exceed the world bounds.
 */


/**
 * @classdesc
 * Provides a source of features for vector layers. Vector features provided
 * by this source are suitable for editing. See {@link module:ol/source/VectorTile~VectorTile} for
 * vector data that is optimized for rendering.
 *
 * @fires ol/source/Vector.VectorSourceEvent
 * @api
 */
var VectorSource = /*@__PURE__*/(function (Source) {
  function VectorSource(opt_options) {

    var options = opt_options || {};

    Source.call(this, {
      attributions: options.attributions,
      projection: undefined,
      state: _State_js__WEBPACK_IMPORTED_MODULE_1__["default"].READY,
      wrapX: options.wrapX !== undefined ? options.wrapX : true
    });

    /**
     * @private
     * @type {import("../featureloader.js").FeatureLoader}
     */
    this.loader_ = _functions_js__WEBPACK_IMPORTED_MODULE_2__.VOID;

    /**
     * @private
     * @type {import("../format/Feature.js").default|undefined}
     */
    this.format_ = options.format;

    /**
     * @private
     * @type {boolean}
     */
    this.overlaps_ = options.overlaps == undefined ? true : options.overlaps;

    /**
     * @private
     * @type {string|import("../featureloader.js").FeatureUrlFunction|undefined}
     */
    this.url_ = options.url;

    if (options.loader !== undefined) {
      this.loader_ = options.loader;
    } else if (this.url_ !== undefined) {
      (0,_asserts_js__WEBPACK_IMPORTED_MODULE_3__.assert)(this.format_, 7); // `format` must be set when `url` is set
      // create a XHR feature loader for "url" and "format"
      this.loader_ = (0,_featureloader_js__WEBPACK_IMPORTED_MODULE_4__.xhr)(this.url_, /** @type {import("../format/Feature.js").default} */ (this.format_));
    }

    /**
     * @private
     * @type {LoadingStrategy}
     */
    this.strategy_ = options.strategy !== undefined ? options.strategy : _loadingstrategy_js__WEBPACK_IMPORTED_MODULE_5__.all;

    var useSpatialIndex =
        options.useSpatialIndex !== undefined ? options.useSpatialIndex : true;

    /**
     * @private
     * @type {RBush<import("../Feature.js").default>}
     */
    this.featuresRtree_ = useSpatialIndex ? new _structs_RBush_js__WEBPACK_IMPORTED_MODULE_6__["default"]() : null;

    /**
     * @private
     * @type {RBush<{extent: import("../extent.js").Extent}>}
     */
    this.loadedExtentsRtree_ = new _structs_RBush_js__WEBPACK_IMPORTED_MODULE_6__["default"]();

    /**
     * @private
     * @type {!Object<string, import("../Feature.js").default>}
     */
    this.nullGeometryFeatures_ = {};

    /**
     * A lookup of features by id (the return from feature.getId()).
     * @private
     * @type {!Object<string, import("../Feature.js").default>}
     */
    this.idIndex_ = {};

    /**
     * A lookup of features without id (keyed by getUid(feature)).
     * @private
     * @type {!Object<string, import("../Feature.js").default>}
     */
    this.undefIdIndex_ = {};

    /**
     * @private
     * @type {Object<string, Array<import("../events.js").EventsKey>>}
     */
    this.featureChangeKeys_ = {};

    /**
     * @private
     * @type {Collection<import("../Feature.js").default>}
     */
    this.featuresCollection_ = null;

    var collection, features;
    if (Array.isArray(options.features)) {
      features = options.features;
    } else if (options.features) {
      collection = options.features;
      features = collection.getArray();
    }
    if (!useSpatialIndex && collection === undefined) {
      collection = new _Collection_js__WEBPACK_IMPORTED_MODULE_7__["default"](features);
    }
    if (features !== undefined) {
      this.addFeaturesInternal(features);
    }
    if (collection !== undefined) {
      this.bindFeaturesCollection_(collection);
    }

  }

  if ( Source ) VectorSource.__proto__ = Source;
  VectorSource.prototype = Object.create( Source && Source.prototype );
  VectorSource.prototype.constructor = VectorSource;

  /**
   * Add a single feature to the source.  If you want to add a batch of features
   * at once, call {@link module:ol/source/Vector~VectorSource#addFeatures #addFeatures()}
   * instead. A feature will not be added to the source if feature with
   * the same id is already there. The reason for this behavior is to avoid
   * feature duplication when using bbox or tile loading strategies.
   * @param {import("../Feature.js").default} feature Feature to add.
   * @api
   */
  VectorSource.prototype.addFeature = function addFeature (feature) {
    this.addFeatureInternal(feature);
    this.changed();
  };


  /**
   * Add a feature without firing a `change` event.
   * @param {import("../Feature.js").default} feature Feature.
   * @protected
   */
  VectorSource.prototype.addFeatureInternal = function addFeatureInternal (feature) {
    var featureKey = (0,_util_js__WEBPACK_IMPORTED_MODULE_8__.getUid)(feature);

    if (!this.addToIndex_(featureKey, feature)) {
      return;
    }

    this.setupChangeEvents_(featureKey, feature);

    var geometry = feature.getGeometry();
    if (geometry) {
      var extent = geometry.getExtent();
      if (this.featuresRtree_) {
        this.featuresRtree_.insert(extent, feature);
      }
    } else {
      this.nullGeometryFeatures_[featureKey] = feature;
    }

    this.dispatchEvent(
      new VectorSourceEvent(_VectorEventType_js__WEBPACK_IMPORTED_MODULE_9__["default"].ADDFEATURE, feature));
  };


  /**
   * @param {string} featureKey Unique identifier for the feature.
   * @param {import("../Feature.js").default} feature The feature.
   * @private
   */
  VectorSource.prototype.setupChangeEvents_ = function setupChangeEvents_ (featureKey, feature) {
    this.featureChangeKeys_[featureKey] = [
      (0,_events_js__WEBPACK_IMPORTED_MODULE_10__.listen)(feature, _events_EventType_js__WEBPACK_IMPORTED_MODULE_11__["default"].CHANGE,
        this.handleFeatureChange_, this),
      (0,_events_js__WEBPACK_IMPORTED_MODULE_10__.listen)(feature, _ObjectEventType_js__WEBPACK_IMPORTED_MODULE_12__["default"].PROPERTYCHANGE,
        this.handleFeatureChange_, this)
    ];
  };


  /**
   * @param {string} featureKey Unique identifier for the feature.
   * @param {import("../Feature.js").default} feature The feature.
   * @return {boolean} The feature is "valid", in the sense that it is also a
   *     candidate for insertion into the Rtree.
   * @private
   */
  VectorSource.prototype.addToIndex_ = function addToIndex_ (featureKey, feature) {
    var valid = true;
    var id = feature.getId();
    if (id !== undefined) {
      if (!(id.toString() in this.idIndex_)) {
        this.idIndex_[id.toString()] = feature;
      } else {
        valid = false;
      }
    } else {
      (0,_asserts_js__WEBPACK_IMPORTED_MODULE_3__.assert)(!(featureKey in this.undefIdIndex_),
        30); // The passed `feature` was already added to the source
      this.undefIdIndex_[featureKey] = feature;
    }
    return valid;
  };


  /**
   * Add a batch of features to the source.
   * @param {Array<import("../Feature.js").default>} features Features to add.
   * @api
   */
  VectorSource.prototype.addFeatures = function addFeatures (features) {
    this.addFeaturesInternal(features);
    this.changed();
  };


  /**
   * Add features without firing a `change` event.
   * @param {Array<import("../Feature.js").default>} features Features.
   * @protected
   */
  VectorSource.prototype.addFeaturesInternal = function addFeaturesInternal (features) {
    var extents = [];
    var newFeatures = [];
    var geometryFeatures = [];

    for (var i = 0, length = features.length; i < length; i++) {
      var feature = features[i];
      var featureKey = (0,_util_js__WEBPACK_IMPORTED_MODULE_8__.getUid)(feature);
      if (this.addToIndex_(featureKey, feature)) {
        newFeatures.push(feature);
      }
    }

    for (var i$1 = 0, length$1 = newFeatures.length; i$1 < length$1; i$1++) {
      var feature$1 = newFeatures[i$1];
      var featureKey$1 = (0,_util_js__WEBPACK_IMPORTED_MODULE_8__.getUid)(feature$1);
      this.setupChangeEvents_(featureKey$1, feature$1);

      var geometry = feature$1.getGeometry();
      if (geometry) {
        var extent = geometry.getExtent();
        extents.push(extent);
        geometryFeatures.push(feature$1);
      } else {
        this.nullGeometryFeatures_[featureKey$1] = feature$1;
      }
    }
    if (this.featuresRtree_) {
      this.featuresRtree_.load(extents, geometryFeatures);
    }

    for (var i$2 = 0, length$2 = newFeatures.length; i$2 < length$2; i$2++) {
      this.dispatchEvent(new VectorSourceEvent(_VectorEventType_js__WEBPACK_IMPORTED_MODULE_9__["default"].ADDFEATURE, newFeatures[i$2]));
    }
  };


  /**
   * @param {!Collection<import("../Feature.js").default>} collection Collection.
   * @private
   */
  VectorSource.prototype.bindFeaturesCollection_ = function bindFeaturesCollection_ (collection) {
    var modifyingCollection = false;
    (0,_events_js__WEBPACK_IMPORTED_MODULE_10__.listen)(this, _VectorEventType_js__WEBPACK_IMPORTED_MODULE_9__["default"].ADDFEATURE,
      /**
       * @param {VectorSourceEvent} evt The vector source event
       */
      function(evt) {
        if (!modifyingCollection) {
          modifyingCollection = true;
          collection.push(evt.feature);
          modifyingCollection = false;
        }
      });
    (0,_events_js__WEBPACK_IMPORTED_MODULE_10__.listen)(this, _VectorEventType_js__WEBPACK_IMPORTED_MODULE_9__["default"].REMOVEFEATURE,
      /**
       * @param {VectorSourceEvent} evt The vector source event
       */
      function(evt) {
        if (!modifyingCollection) {
          modifyingCollection = true;
          collection.remove(evt.feature);
          modifyingCollection = false;
        }
      });
    (0,_events_js__WEBPACK_IMPORTED_MODULE_10__.listen)(collection, _CollectionEventType_js__WEBPACK_IMPORTED_MODULE_13__["default"].ADD,
      /**
       * @param {import("../Collection.js").CollectionEvent} evt The collection event
       */
      function(evt) {
        if (!modifyingCollection) {
          modifyingCollection = true;
          this.addFeature(/** @type {import("../Feature.js").default} */ (evt.element));
          modifyingCollection = false;
        }
      }, this);
    (0,_events_js__WEBPACK_IMPORTED_MODULE_10__.listen)(collection, _CollectionEventType_js__WEBPACK_IMPORTED_MODULE_13__["default"].REMOVE,
      /**
       * @param {import("../Collection.js").CollectionEvent} evt The collection event
       */
      function(evt) {
        if (!modifyingCollection) {
          modifyingCollection = true;
          this.removeFeature(/** @type {import("../Feature.js").default} */ (evt.element));
          modifyingCollection = false;
        }
      }, this);
    this.featuresCollection_ = collection;
  };


  /**
   * Remove all features from the source.
   * @param {boolean=} opt_fast Skip dispatching of {@link module:ol/source/Vector.VectorSourceEvent#removefeature} events.
   * @api
   */
  VectorSource.prototype.clear = function clear (opt_fast) {
    if (opt_fast) {
      for (var featureId in this.featureChangeKeys_) {
        var keys = this.featureChangeKeys_[featureId];
        keys.forEach(_events_js__WEBPACK_IMPORTED_MODULE_10__.unlistenByKey);
      }
      if (!this.featuresCollection_) {
        this.featureChangeKeys_ = {};
        this.idIndex_ = {};
        this.undefIdIndex_ = {};
      }
    } else {
      if (this.featuresRtree_) {
        this.featuresRtree_.forEach(this.removeFeatureInternal, this);
        for (var id in this.nullGeometryFeatures_) {
          this.removeFeatureInternal(this.nullGeometryFeatures_[id]);
        }
      }
    }
    if (this.featuresCollection_) {
      this.featuresCollection_.clear();
    }

    if (this.featuresRtree_) {
      this.featuresRtree_.clear();
    }
    this.loadedExtentsRtree_.clear();
    this.nullGeometryFeatures_ = {};

    var clearEvent = new VectorSourceEvent(_VectorEventType_js__WEBPACK_IMPORTED_MODULE_9__["default"].CLEAR);
    this.dispatchEvent(clearEvent);
    this.changed();
  };


  /**
   * Iterate through all features on the source, calling the provided callback
   * with each one.  If the callback returns any "truthy" value, iteration will
   * stop and the function will return the same value.
   * Note: this function only iterate through the feature that have a defined geometry.
   *
   * @param {function(import("../Feature.js").default): T} callback Called with each feature
   *     on the source.  Return a truthy value to stop iteration.
   * @return {T|undefined} The return value from the last call to the callback.
   * @template T
   * @api
   */
  VectorSource.prototype.forEachFeature = function forEachFeature (callback) {
    if (this.featuresRtree_) {
      return this.featuresRtree_.forEach(callback);
    } else if (this.featuresCollection_) {
      this.featuresCollection_.forEach(callback);
    }
  };


  /**
   * Iterate through all features whose geometries contain the provided
   * coordinate, calling the callback with each feature.  If the callback returns
   * a "truthy" value, iteration will stop and the function will return the same
   * value.
   *
   * @param {import("../coordinate.js").Coordinate} coordinate Coordinate.
   * @param {function(import("../Feature.js").default): T} callback Called with each feature
   *     whose goemetry contains the provided coordinate.
   * @return {T|undefined} The return value from the last call to the callback.
   * @template T
   */
  VectorSource.prototype.forEachFeatureAtCoordinateDirect = function forEachFeatureAtCoordinateDirect (coordinate, callback) {
    var extent = [coordinate[0], coordinate[1], coordinate[0], coordinate[1]];
    return this.forEachFeatureInExtent(extent, function(feature) {
      var geometry = feature.getGeometry();
      if (geometry.intersectsCoordinate(coordinate)) {
        return callback(feature);
      } else {
        return undefined;
      }
    });
  };


  /**
   * Iterate through all features whose bounding box intersects the provided
   * extent (note that the feature's geometry may not intersect the extent),
   * calling the callback with each feature.  If the callback returns a "truthy"
   * value, iteration will stop and the function will return the same value.
   *
   * If you are interested in features whose geometry intersects an extent, call
   * the {@link module:ol/source/Vector~VectorSource#forEachFeatureIntersectingExtent #forEachFeatureIntersectingExtent()} method instead.
   *
   * When `useSpatialIndex` is set to false, this method will loop through all
   * features, equivalent to {@link module:ol/source/Vector~VectorSource#forEachFeature #forEachFeature()}.
   *
   * @param {import("../extent.js").Extent} extent Extent.
   * @param {function(import("../Feature.js").default): T} callback Called with each feature
   *     whose bounding box intersects the provided extent.
   * @return {T|undefined} The return value from the last call to the callback.
   * @template T
   * @api
   */
  VectorSource.prototype.forEachFeatureInExtent = function forEachFeatureInExtent (extent, callback) {
    if (this.featuresRtree_) {
      return this.featuresRtree_.forEachInExtent(extent, callback);
    } else if (this.featuresCollection_) {
      this.featuresCollection_.forEach(callback);
    }
  };


  /**
   * Iterate through all features whose geometry intersects the provided extent,
   * calling the callback with each feature.  If the callback returns a "truthy"
   * value, iteration will stop and the function will return the same value.
   *
   * If you only want to test for bounding box intersection, call the
   * {@link module:ol/source/Vector~VectorSource#forEachFeatureInExtent #forEachFeatureInExtent()} method instead.
   *
   * @param {import("../extent.js").Extent} extent Extent.
   * @param {function(import("../Feature.js").default): T} callback Called with each feature
   *     whose geometry intersects the provided extent.
   * @return {T|undefined} The return value from the last call to the callback.
   * @template T
   * @api
   */
  VectorSource.prototype.forEachFeatureIntersectingExtent = function forEachFeatureIntersectingExtent (extent, callback) {
    return this.forEachFeatureInExtent(extent,
      /**
       * @param {import("../Feature.js").default} feature Feature.
       * @return {T|undefined} The return value from the last call to the callback.
       */
      function(feature) {
        var geometry = feature.getGeometry();
        if (geometry.intersectsExtent(extent)) {
          var result = callback(feature);
          if (result) {
            return result;
          }
        }
      });
  };


  /**
   * Get the features collection associated with this source. Will be `null`
   * unless the source was configured with `useSpatialIndex` set to `false`, or
   * with an {@link module:ol/Collection} as `features`.
   * @return {Collection<import("../Feature.js").default>} The collection of features.
   * @api
   */
  VectorSource.prototype.getFeaturesCollection = function getFeaturesCollection () {
    return this.featuresCollection_;
  };


  /**
   * Get all features on the source in random order.
   * @return {Array<import("../Feature.js").default>} Features.
   * @api
   */
  VectorSource.prototype.getFeatures = function getFeatures () {
    var features;
    if (this.featuresCollection_) {
      features = this.featuresCollection_.getArray();
    } else if (this.featuresRtree_) {
      features = this.featuresRtree_.getAll();
      if (!(0,_obj_js__WEBPACK_IMPORTED_MODULE_14__.isEmpty)(this.nullGeometryFeatures_)) {
        (0,_array_js__WEBPACK_IMPORTED_MODULE_15__.extend)(features, (0,_obj_js__WEBPACK_IMPORTED_MODULE_14__.getValues)(this.nullGeometryFeatures_));
      }
    }
    return (
      /** @type {Array<import("../Feature.js").default>} */ (features)
    );
  };


  /**
   * Get all features whose geometry intersects the provided coordinate.
   * @param {import("../coordinate.js").Coordinate} coordinate Coordinate.
   * @return {Array<import("../Feature.js").default>} Features.
   * @api
   */
  VectorSource.prototype.getFeaturesAtCoordinate = function getFeaturesAtCoordinate (coordinate) {
    var features = [];
    this.forEachFeatureAtCoordinateDirect(coordinate, function(feature) {
      features.push(feature);
    });
    return features;
  };


  /**
   * Get all features in the provided extent.  Note that this returns an array of
   * all features intersecting the given extent in random order (so it may include
   * features whose geometries do not intersect the extent).
   *
   * This method is not available when the source is configured with
   * `useSpatialIndex` set to `false`.
   * @param {import("../extent.js").Extent} extent Extent.
   * @return {Array<import("../Feature.js").default>} Features.
   * @api
   */
  VectorSource.prototype.getFeaturesInExtent = function getFeaturesInExtent (extent) {
    return this.featuresRtree_.getInExtent(extent);
  };


  /**
   * Get the closest feature to the provided coordinate.
   *
   * This method is not available when the source is configured with
   * `useSpatialIndex` set to `false`.
   * @param {import("../coordinate.js").Coordinate} coordinate Coordinate.
   * @param {function(import("../Feature.js").default):boolean=} opt_filter Feature filter function.
   *     The filter function will receive one argument, the {@link module:ol/Feature feature}
   *     and it should return a boolean value. By default, no filtering is made.
   * @return {import("../Feature.js").default} Closest feature.
   * @api
   */
  VectorSource.prototype.getClosestFeatureToCoordinate = function getClosestFeatureToCoordinate (coordinate, opt_filter) {
    // Find the closest feature using branch and bound.  We start searching an
    // infinite extent, and find the distance from the first feature found.  This
    // becomes the closest feature.  We then compute a smaller extent which any
    // closer feature must intersect.  We continue searching with this smaller
    // extent, trying to find a closer feature.  Every time we find a closer
    // feature, we update the extent being searched so that any even closer
    // feature must intersect it.  We continue until we run out of features.
    var x = coordinate[0];
    var y = coordinate[1];
    var closestFeature = null;
    var closestPoint = [NaN, NaN];
    var minSquaredDistance = Infinity;
    var extent = [-Infinity, -Infinity, Infinity, Infinity];
    var filter = opt_filter ? opt_filter : _functions_js__WEBPACK_IMPORTED_MODULE_2__.TRUE;
    this.featuresRtree_.forEachInExtent(extent,
      /**
       * @param {import("../Feature.js").default} feature Feature.
       */
      function(feature) {
        if (filter(feature)) {
          var geometry = feature.getGeometry();
          var previousMinSquaredDistance = minSquaredDistance;
          minSquaredDistance = geometry.closestPointXY(
            x, y, closestPoint, minSquaredDistance);
          if (minSquaredDistance < previousMinSquaredDistance) {
            closestFeature = feature;
            // This is sneaky.  Reduce the extent that it is currently being
            // searched while the R-Tree traversal using this same extent object
            // is still in progress.  This is safe because the new extent is
            // strictly contained by the old extent.
            var minDistance = Math.sqrt(minSquaredDistance);
            extent[0] = x - minDistance;
            extent[1] = y - minDistance;
            extent[2] = x + minDistance;
            extent[3] = y + minDistance;
          }
        }
      });
    return closestFeature;
  };


  /**
   * Get the extent of the features currently in the source.
   *
   * This method is not available when the source is configured with
   * `useSpatialIndex` set to `false`.
   * @param {import("../extent.js").Extent=} opt_extent Destination extent. If provided, no new extent
   *     will be created. Instead, that extent's coordinates will be overwritten.
   * @return {import("../extent.js").Extent} Extent.
   * @api
   */
  VectorSource.prototype.getExtent = function getExtent (opt_extent) {
    return this.featuresRtree_.getExtent(opt_extent);
  };


  /**
   * Get a feature by its identifier (the value returned by feature.getId()).
   * Note that the index treats string and numeric identifiers as the same.  So
   * `source.getFeatureById(2)` will return a feature with id `'2'` or `2`.
   *
   * @param {string|number} id Feature identifier.
   * @return {import("../Feature.js").default} The feature (or `null` if not found).
   * @api
   */
  VectorSource.prototype.getFeatureById = function getFeatureById (id) {
    var feature = this.idIndex_[id.toString()];
    return feature !== undefined ? feature : null;
  };


  /**
   * Get the format associated with this source.
   *
   * @return {import("../format/Feature.js").default|undefined} The feature format.
   * @api
   */
  VectorSource.prototype.getFormat = function getFormat () {
    return this.format_;
  };


  /**
   * @return {boolean} The source can have overlapping geometries.
   */
  VectorSource.prototype.getOverlaps = function getOverlaps () {
    return this.overlaps_;
  };


  /**
   * Get the url associated with this source.
   *
   * @return {string|import("../featureloader.js").FeatureUrlFunction|undefined} The url.
   * @api
   */
  VectorSource.prototype.getUrl = function getUrl () {
    return this.url_;
  };


  /**
   * @param {Event} event Event.
   * @private
   */
  VectorSource.prototype.handleFeatureChange_ = function handleFeatureChange_ (event) {
    var feature = /** @type {import("../Feature.js").default} */ (event.target);
    var featureKey = (0,_util_js__WEBPACK_IMPORTED_MODULE_8__.getUid)(feature);
    var geometry = feature.getGeometry();
    if (!geometry) {
      if (!(featureKey in this.nullGeometryFeatures_)) {
        if (this.featuresRtree_) {
          this.featuresRtree_.remove(feature);
        }
        this.nullGeometryFeatures_[featureKey] = feature;
      }
    } else {
      var extent = geometry.getExtent();
      if (featureKey in this.nullGeometryFeatures_) {
        delete this.nullGeometryFeatures_[featureKey];
        if (this.featuresRtree_) {
          this.featuresRtree_.insert(extent, feature);
        }
      } else {
        if (this.featuresRtree_) {
          this.featuresRtree_.update(extent, feature);
        }
      }
    }
    var id = feature.getId();
    if (id !== undefined) {
      var sid = id.toString();
      if (featureKey in this.undefIdIndex_) {
        delete this.undefIdIndex_[featureKey];
        this.idIndex_[sid] = feature;
      } else {
        if (this.idIndex_[sid] !== feature) {
          this.removeFromIdIndex_(feature);
          this.idIndex_[sid] = feature;
        }
      }
    } else {
      if (!(featureKey in this.undefIdIndex_)) {
        this.removeFromIdIndex_(feature);
        this.undefIdIndex_[featureKey] = feature;
      }
    }
    this.changed();
    this.dispatchEvent(new VectorSourceEvent(
      _VectorEventType_js__WEBPACK_IMPORTED_MODULE_9__["default"].CHANGEFEATURE, feature));
  };

  /**
   * Returns true if the feature is contained within the source.
   * @param {import("../Feature.js").default} feature Feature.
   * @return {boolean} Has feature.
   * @api
   */
  VectorSource.prototype.hasFeature = function hasFeature (feature) {
    var id = feature.getId();
    if (id !== undefined) {
      return id in this.idIndex_;
    } else {
      return (0,_util_js__WEBPACK_IMPORTED_MODULE_8__.getUid)(feature) in this.undefIdIndex_;
    }
  };

  /**
   * @return {boolean} Is empty.
   */
  VectorSource.prototype.isEmpty = function isEmpty$1 () {
    return this.featuresRtree_.isEmpty() && (0,_obj_js__WEBPACK_IMPORTED_MODULE_14__.isEmpty)(this.nullGeometryFeatures_);
  };


  /**
   * @param {import("../extent.js").Extent} extent Extent.
   * @param {number} resolution Resolution.
   * @param {import("../proj/Projection.js").default} projection Projection.
   */
  VectorSource.prototype.loadFeatures = function loadFeatures (extent, resolution, projection) {
    var this$1 = this;

    var loadedExtentsRtree = this.loadedExtentsRtree_;
    var extentsToLoad = this.strategy_(extent, resolution);
    this.loading = false;
    var loop = function ( i, ii ) {
      var extentToLoad = extentsToLoad[i];
      var alreadyLoaded = loadedExtentsRtree.forEachInExtent(extentToLoad,
        /**
         * @param {{extent: import("../extent.js").Extent}} object Object.
         * @return {boolean} Contains.
         */
        function(object) {
          return (0,_extent_js__WEBPACK_IMPORTED_MODULE_16__.containsExtent)(object.extent, extentToLoad);
        });
      if (!alreadyLoaded) {
        this$1.loader_.call(this$1, extentToLoad, resolution, projection);
        loadedExtentsRtree.insert(extentToLoad, {extent: extentToLoad.slice()});
        this$1.loading = this$1.loader_ !== _functions_js__WEBPACK_IMPORTED_MODULE_2__.VOID;
      }
    };

    for (var i = 0, ii = extentsToLoad.length; i < ii; ++i) loop( i, ii );
  };


  /**
   * Remove an extent from the list of loaded extents.
   * @param {import("../extent.js").Extent} extent Extent.
   * @api
   */
  VectorSource.prototype.removeLoadedExtent = function removeLoadedExtent (extent) {
    var loadedExtentsRtree = this.loadedExtentsRtree_;
    var obj;
    loadedExtentsRtree.forEachInExtent(extent, function(object) {
      if ((0,_extent_js__WEBPACK_IMPORTED_MODULE_16__.equals)(object.extent, extent)) {
        obj = object;
        return true;
      }
    });
    if (obj) {
      loadedExtentsRtree.remove(obj);
    }
  };


  /**
   * Remove a single feature from the source.  If you want to remove all features
   * at once, use the {@link module:ol/source/Vector~VectorSource#clear #clear()} method
   * instead.
   * @param {import("../Feature.js").default} feature Feature to remove.
   * @api
   */
  VectorSource.prototype.removeFeature = function removeFeature (feature) {
    var featureKey = (0,_util_js__WEBPACK_IMPORTED_MODULE_8__.getUid)(feature);
    if (featureKey in this.nullGeometryFeatures_) {
      delete this.nullGeometryFeatures_[featureKey];
    } else {
      if (this.featuresRtree_) {
        this.featuresRtree_.remove(feature);
      }
    }
    this.removeFeatureInternal(feature);
    this.changed();
  };


  /**
   * Remove feature without firing a `change` event.
   * @param {import("../Feature.js").default} feature Feature.
   * @protected
   */
  VectorSource.prototype.removeFeatureInternal = function removeFeatureInternal (feature) {
    var featureKey = (0,_util_js__WEBPACK_IMPORTED_MODULE_8__.getUid)(feature);
    this.featureChangeKeys_[featureKey].forEach(_events_js__WEBPACK_IMPORTED_MODULE_10__.unlistenByKey);
    delete this.featureChangeKeys_[featureKey];
    var id = feature.getId();
    if (id !== undefined) {
      delete this.idIndex_[id.toString()];
    } else {
      delete this.undefIdIndex_[featureKey];
    }
    this.dispatchEvent(new VectorSourceEvent(
      _VectorEventType_js__WEBPACK_IMPORTED_MODULE_9__["default"].REMOVEFEATURE, feature));
  };


  /**
   * Remove a feature from the id index.  Called internally when the feature id
   * may have changed.
   * @param {import("../Feature.js").default} feature The feature.
   * @return {boolean} Removed the feature from the index.
   * @private
   */
  VectorSource.prototype.removeFromIdIndex_ = function removeFromIdIndex_ (feature) {
    var removed = false;
    for (var id in this.idIndex_) {
      if (this.idIndex_[id] === feature) {
        delete this.idIndex_[id];
        removed = true;
        break;
      }
    }
    return removed;
  };


  /**
   * Set the new loader of the source. The next loadFeatures call will use the
   * new loader.
   * @param {import("../featureloader.js").FeatureLoader} loader The loader to set.
   * @api
   */
  VectorSource.prototype.setLoader = function setLoader (loader) {
    this.loader_ = loader;
  };

  return VectorSource;
}(_Source_js__WEBPACK_IMPORTED_MODULE_17__["default"]));


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (VectorSource);

//# sourceMappingURL=Vector.js.map

/***/ }),

/***/ "./node_modules/ol/source/VectorEventType.js":
/*!***************************************************!*\
  !*** ./node_modules/ol/source/VectorEventType.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @module ol/source/VectorEventType
 */

/**
 * @enum {string}
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  /**
   * Triggered when a feature is added to the source.
   * @event ol/source/Vector.VectorSourceEvent#addfeature
   * @api
   */
  ADDFEATURE: 'addfeature',

  /**
   * Triggered when a feature is updated.
   * @event ol/source/Vector.VectorSourceEvent#changefeature
   * @api
   */
  CHANGEFEATURE: 'changefeature',

  /**
   * Triggered when the clear method is called on the source.
   * @event ol/source/Vector.VectorSourceEvent#clear
   * @api
   */
  CLEAR: 'clear',

  /**
   * Triggered when a feature is removed from the source.
   * See {@link module:ol/source/Vector#clear source.clear()} for exceptions.
   * @event ol/source/Vector.VectorSourceEvent#removefeature
   * @api
   */
  REMOVEFEATURE: 'removefeature'
});

//# sourceMappingURL=VectorEventType.js.map

/***/ }),

/***/ "./node_modules/ol/sphere.js":
/*!***********************************!*\
  !*** ./node_modules/ol/sphere.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "DEFAULT_RADIUS": () => (/* binding */ DEFAULT_RADIUS),
/* harmony export */   "getDistance": () => (/* binding */ getDistance),
/* harmony export */   "getLength": () => (/* binding */ getLength),
/* harmony export */   "getArea": () => (/* binding */ getArea),
/* harmony export */   "offset": () => (/* binding */ offset)
/* harmony export */ });
/* harmony import */ var _math_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./math.js */ "./node_modules/ol/math.js");
/* harmony import */ var _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./geom/GeometryType.js */ "./node_modules/ol/geom/GeometryType.js");
/**
 * @license
 * Latitude/longitude spherical geodesy formulae taken from
 * http://www.movable-type.co.uk/scripts/latlong.html
 * Licensed under CC-BY-3.0.
 */

/**
 * @module ol/sphere
 */




/**
 * Object literal with options for the {@link getLength} or {@link getArea}
 * functions.
 * @typedef {Object} SphereMetricOptions
 * @property {import("./proj.js").ProjectionLike} [projection='EPSG:3857']
 * Projection of the  geometry.  By default, the geometry is assumed to be in
 * Web Mercator.
 * @property {number} [radius=6371008.8] Sphere radius.  By default, the radius of the
 * earth is used (Clarke 1866 Authalic Sphere).
 */


/**
 * The mean Earth radius (1/3 * (2a + b)) for the WGS84 ellipsoid.
 * https://en.wikipedia.org/wiki/Earth_radius#Mean_radius
 * @type {number}
 */
var DEFAULT_RADIUS = 6371008.8;


/**
 * Get the great circle distance (in meters) between two geographic coordinates.
 * @param {Array} c1 Starting coordinate.
 * @param {Array} c2 Ending coordinate.
 * @param {number=} opt_radius The sphere radius to use.  Defaults to the Earth's
 *     mean radius using the WGS84 ellipsoid.
 * @return {number} The great circle distance between the points (in meters).
 * @api
 */
function getDistance(c1, c2, opt_radius) {
  var radius = opt_radius || DEFAULT_RADIUS;
  var lat1 = (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toRadians)(c1[1]);
  var lat2 = (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toRadians)(c2[1]);
  var deltaLatBy2 = (lat2 - lat1) / 2;
  var deltaLonBy2 = (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toRadians)(c2[0] - c1[0]) / 2;
  var a = Math.sin(deltaLatBy2) * Math.sin(deltaLatBy2) +
      Math.sin(deltaLonBy2) * Math.sin(deltaLonBy2) *
      Math.cos(lat1) * Math.cos(lat2);
  return 2 * radius * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}


/**
 * Get the cumulative great circle length of linestring coordinates (geographic).
 * @param {Array} coordinates Linestring coordinates.
 * @param {number} radius The sphere radius to use.
 * @return {number} The length (in meters).
 */
function getLengthInternal(coordinates, radius) {
  var length = 0;
  for (var i = 0, ii = coordinates.length; i < ii - 1; ++i) {
    length += getDistance(coordinates[i], coordinates[i + 1], radius);
  }
  return length;
}


/**
 * Get the spherical length of a geometry.  This length is the sum of the
 * great circle distances between coordinates.  For polygons, the length is
 * the sum of all rings.  For points, the length is zero.  For multi-part
 * geometries, the length is the sum of the length of each part.
 * @param {import("./geom/Geometry.js").default} geometry A geometry.
 * @param {SphereMetricOptions=} opt_options Options for the
 * length calculation.  By default, geometries are assumed to be in 'EPSG:3857'.
 * You can change this by providing a `projection` option.
 * @return {number} The spherical length (in meters).
 * @api
 */
function getLength(geometry, opt_options) {
  var options = opt_options || {};
  var radius = options.radius || DEFAULT_RADIUS;
  var projection = options.projection || 'EPSG:3857';
  var type = geometry.getType();
  if (type !== _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].GEOMETRY_COLLECTION) {
    geometry = geometry.clone().transform(projection, 'EPSG:4326');
  }
  var length = 0;
  var coordinates, coords, i, ii, j, jj;
  switch (type) {
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].POINT:
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].MULTI_POINT: {
      break;
    }
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].LINE_STRING:
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].LINEAR_RING: {
      coordinates = /** @type {import("./geom/SimpleGeometry.js").default} */ (geometry).getCoordinates();
      length = getLengthInternal(coordinates, radius);
      break;
    }
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].MULTI_LINE_STRING:
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].POLYGON: {
      coordinates = /** @type {import("./geom/SimpleGeometry.js").default} */ (geometry).getCoordinates();
      for (i = 0, ii = coordinates.length; i < ii; ++i) {
        length += getLengthInternal(coordinates[i], radius);
      }
      break;
    }
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].MULTI_POLYGON: {
      coordinates = /** @type {import("./geom/SimpleGeometry.js").default} */ (geometry).getCoordinates();
      for (i = 0, ii = coordinates.length; i < ii; ++i) {
        coords = coordinates[i];
        for (j = 0, jj = coords.length; j < jj; ++j) {
          length += getLengthInternal(coords[j], radius);
        }
      }
      break;
    }
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].GEOMETRY_COLLECTION: {
      var geometries = /** @type {import("./geom/GeometryCollection.js").default} */ (geometry).getGeometries();
      for (i = 0, ii = geometries.length; i < ii; ++i) {
        length += getLength(geometries[i], opt_options);
      }
      break;
    }
    default: {
      throw new Error('Unsupported geometry type: ' + type);
    }
  }
  return length;
}


/**
 * Returns the spherical area for a list of coordinates.
 *
 * [Reference](https://trs-new.jpl.nasa.gov/handle/2014/40409)
 * Robert. G. Chamberlain and William H. Duquette, "Some Algorithms for
 * Polygons on a Sphere", JPL Publication 07-03, Jet Propulsion
 * Laboratory, Pasadena, CA, June 2007
 *
 * @param {Array<import("./coordinate.js").Coordinate>} coordinates List of coordinates of a linear
 * ring. If the ring is oriented clockwise, the area will be positive,
 * otherwise it will be negative.
 * @param {number} radius The sphere radius.
 * @return {number} Area (in square meters).
 */
function getAreaInternal(coordinates, radius) {
  var area = 0;
  var len = coordinates.length;
  var x1 = coordinates[len - 1][0];
  var y1 = coordinates[len - 1][1];
  for (var i = 0; i < len; i++) {
    var x2 = coordinates[i][0];
    var y2 = coordinates[i][1];
    area += (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toRadians)(x2 - x1) *
        (2 + Math.sin((0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toRadians)(y1)) +
        Math.sin((0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toRadians)(y2)));
    x1 = x2;
    y1 = y2;
  }
  return area * radius * radius / 2.0;
}


/**
 * Get the spherical area of a geometry.  This is the area (in meters) assuming
 * that polygon edges are segments of great circles on a sphere.
 * @param {import("./geom/Geometry.js").default} geometry A geometry.
 * @param {SphereMetricOptions=} opt_options Options for the area
 *     calculation.  By default, geometries are assumed to be in 'EPSG:3857'.
 *     You can change this by providing a `projection` option.
 * @return {number} The spherical area (in square meters).
 * @api
 */
function getArea(geometry, opt_options) {
  var options = opt_options || {};
  var radius = options.radius || DEFAULT_RADIUS;
  var projection = options.projection || 'EPSG:3857';
  var type = geometry.getType();
  if (type !== _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].GEOMETRY_COLLECTION) {
    geometry = geometry.clone().transform(projection, 'EPSG:4326');
  }
  var area = 0;
  var coordinates, coords, i, ii, j, jj;
  switch (type) {
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].POINT:
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].MULTI_POINT:
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].LINE_STRING:
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].MULTI_LINE_STRING:
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].LINEAR_RING: {
      break;
    }
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].POLYGON: {
      coordinates = /** @type {import("./geom/Polygon.js").default} */ (geometry).getCoordinates();
      area = Math.abs(getAreaInternal(coordinates[0], radius));
      for (i = 1, ii = coordinates.length; i < ii; ++i) {
        area -= Math.abs(getAreaInternal(coordinates[i], radius));
      }
      break;
    }
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].MULTI_POLYGON: {
      coordinates = /** @type {import("./geom/SimpleGeometry.js").default} */ (geometry).getCoordinates();
      for (i = 0, ii = coordinates.length; i < ii; ++i) {
        coords = coordinates[i];
        area += Math.abs(getAreaInternal(coords[0], radius));
        for (j = 1, jj = coords.length; j < jj; ++j) {
          area -= Math.abs(getAreaInternal(coords[j], radius));
        }
      }
      break;
    }
    case _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_1__["default"].GEOMETRY_COLLECTION: {
      var geometries = /** @type {import("./geom/GeometryCollection.js").default} */ (geometry).getGeometries();
      for (i = 0, ii = geometries.length; i < ii; ++i) {
        area += getArea(geometries[i], opt_options);
      }
      break;
    }
    default: {
      throw new Error('Unsupported geometry type: ' + type);
    }
  }
  return area;
}


/**
 * Returns the coordinate at the given distance and bearing from `c1`.
 *
 * @param {import("./coordinate.js").Coordinate} c1 The origin point (`[lon, lat]` in degrees).
 * @param {number} distance The great-circle distance between the origin
 *     point and the target point.
 * @param {number} bearing The bearing (in radians).
 * @param {number=} opt_radius The sphere radius to use.  Defaults to the Earth's
 *     mean radius using the WGS84 ellipsoid.
 * @return {import("./coordinate.js").Coordinate} The target point.
 */
function offset(c1, distance, bearing, opt_radius) {
  var radius = opt_radius || DEFAULT_RADIUS;
  var lat1 = (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toRadians)(c1[1]);
  var lon1 = (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toRadians)(c1[0]);
  var dByR = distance / radius;
  var lat = Math.asin(
    Math.sin(lat1) * Math.cos(dByR) +
      Math.cos(lat1) * Math.sin(dByR) * Math.cos(bearing));
  var lon = lon1 + Math.atan2(
    Math.sin(bearing) * Math.sin(dByR) * Math.cos(lat1),
    Math.cos(dByR) - Math.sin(lat1) * Math.sin(lat));
  return [(0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toDegrees)(lon), (0,_math_js__WEBPACK_IMPORTED_MODULE_0__.toDegrees)(lat)];
}

//# sourceMappingURL=sphere.js.map

/***/ }),

/***/ "./node_modules/ol/structs/LRUCache.js":
/*!*********************************************!*\
  !*** ./node_modules/ol/structs/LRUCache.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _asserts_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../asserts.js */ "./node_modules/ol/asserts.js");
/* harmony import */ var _events_Target_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../events/Target.js */ "./node_modules/ol/events/Target.js");
/* harmony import */ var _events_EventType_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../events/EventType.js */ "./node_modules/ol/events/EventType.js");
/**
 * @module ol/structs/LRUCache
 */






/**
 * @typedef {Object} Entry
 * @property {string} key_
 * @property {Object} newer
 * @property {Object} older
 * @property {*} value_
 */


/**
 * @classdesc
 * Implements a Least-Recently-Used cache where the keys do not conflict with
 * Object's properties (e.g. 'hasOwnProperty' is not allowed as a key). Expiring
 * items from the cache is the responsibility of the user.
 *
 * @fires import("../events/Event.js").Event
 * @template T
 */
var LRUCache = /*@__PURE__*/(function (EventTarget) {
  function LRUCache(opt_highWaterMark) {

    EventTarget.call(this);

    /**
     * @type {number}
     */
    this.highWaterMark = opt_highWaterMark !== undefined ? opt_highWaterMark : 2048;

    /**
     * @private
     * @type {number}
     */
    this.count_ = 0;

    /**
     * @private
     * @type {!Object<string, Entry>}
     */
    this.entries_ = {};

    /**
     * @private
     * @type {?Entry}
     */
    this.oldest_ = null;

    /**
     * @private
     * @type {?Entry}
     */
    this.newest_ = null;

  }

  if ( EventTarget ) LRUCache.__proto__ = EventTarget;
  LRUCache.prototype = Object.create( EventTarget && EventTarget.prototype );
  LRUCache.prototype.constructor = LRUCache;


  /**
   * @return {boolean} Can expire cache.
   */
  LRUCache.prototype.canExpireCache = function canExpireCache () {
    return this.getCount() > this.highWaterMark;
  };


  /**
   * FIXME empty description for jsdoc
   */
  LRUCache.prototype.clear = function clear () {
    this.count_ = 0;
    this.entries_ = {};
    this.oldest_ = null;
    this.newest_ = null;
    this.dispatchEvent(_events_EventType_js__WEBPACK_IMPORTED_MODULE_0__["default"].CLEAR);
  };


  /**
   * @param {string} key Key.
   * @return {boolean} Contains key.
   */
  LRUCache.prototype.containsKey = function containsKey (key) {
    return this.entries_.hasOwnProperty(key);
  };


  /**
   * @param {function(this: S, T, string, LRUCache): ?} f The function
   *     to call for every entry from the oldest to the newer. This function takes
   *     3 arguments (the entry value, the entry key and the LRUCache object).
   *     The return value is ignored.
   * @param {S=} opt_this The object to use as `this` in `f`.
   * @template S
   */
  LRUCache.prototype.forEach = function forEach (f, opt_this) {
    var entry = this.oldest_;
    while (entry) {
      f.call(opt_this, entry.value_, entry.key_, this);
      entry = entry.newer;
    }
  };


  /**
   * @param {string} key Key.
   * @return {T} Value.
   */
  LRUCache.prototype.get = function get (key) {
    var entry = this.entries_[key];
    (0,_asserts_js__WEBPACK_IMPORTED_MODULE_1__.assert)(entry !== undefined,
      15); // Tried to get a value for a key that does not exist in the cache
    if (entry === this.newest_) {
      return entry.value_;
    } else if (entry === this.oldest_) {
      this.oldest_ = /** @type {Entry} */ (this.oldest_.newer);
      this.oldest_.older = null;
    } else {
      entry.newer.older = entry.older;
      entry.older.newer = entry.newer;
    }
    entry.newer = null;
    entry.older = this.newest_;
    this.newest_.newer = entry;
    this.newest_ = entry;
    return entry.value_;
  };


  /**
   * Remove an entry from the cache.
   * @param {string} key The entry key.
   * @return {T} The removed entry.
   */
  LRUCache.prototype.remove = function remove (key) {
    var entry = this.entries_[key];
    (0,_asserts_js__WEBPACK_IMPORTED_MODULE_1__.assert)(entry !== undefined, 15); // Tried to get a value for a key that does not exist in the cache
    if (entry === this.newest_) {
      this.newest_ = /** @type {Entry} */ (entry.older);
      if (this.newest_) {
        this.newest_.newer = null;
      }
    } else if (entry === this.oldest_) {
      this.oldest_ = /** @type {Entry} */ (entry.newer);
      if (this.oldest_) {
        this.oldest_.older = null;
      }
    } else {
      entry.newer.older = entry.older;
      entry.older.newer = entry.newer;
    }
    delete this.entries_[key];
    --this.count_;
    return entry.value_;
  };


  /**
   * @return {number} Count.
   */
  LRUCache.prototype.getCount = function getCount () {
    return this.count_;
  };


  /**
   * @return {Array<string>} Keys.
   */
  LRUCache.prototype.getKeys = function getKeys () {
    var keys = new Array(this.count_);
    var i = 0;
    var entry;
    for (entry = this.newest_; entry; entry = entry.older) {
      keys[i++] = entry.key_;
    }
    return keys;
  };


  /**
   * @return {Array<T>} Values.
   */
  LRUCache.prototype.getValues = function getValues () {
    var values = new Array(this.count_);
    var i = 0;
    var entry;
    for (entry = this.newest_; entry; entry = entry.older) {
      values[i++] = entry.value_;
    }
    return values;
  };


  /**
   * @return {T} Last value.
   */
  LRUCache.prototype.peekLast = function peekLast () {
    return this.oldest_.value_;
  };


  /**
   * @return {string} Last key.
   */
  LRUCache.prototype.peekLastKey = function peekLastKey () {
    return this.oldest_.key_;
  };


  /**
   * Get the key of the newest item in the cache.  Throws if the cache is empty.
   * @return {string} The newest key.
   */
  LRUCache.prototype.peekFirstKey = function peekFirstKey () {
    return this.newest_.key_;
  };


  /**
   * @return {T} value Value.
   */
  LRUCache.prototype.pop = function pop () {
    var entry = this.oldest_;
    delete this.entries_[entry.key_];
    if (entry.newer) {
      entry.newer.older = null;
    }
    this.oldest_ = /** @type {Entry} */ (entry.newer);
    if (!this.oldest_) {
      this.newest_ = null;
    }
    --this.count_;
    return entry.value_;
  };


  /**
   * @param {string} key Key.
   * @param {T} value Value.
   */
  LRUCache.prototype.replace = function replace (key, value) {
    this.get(key); // update `newest_`
    this.entries_[key].value_ = value;
  };


  /**
   * @param {string} key Key.
   * @param {T} value Value.
   */
  LRUCache.prototype.set = function set (key, value) {
    (0,_asserts_js__WEBPACK_IMPORTED_MODULE_1__.assert)(!(key in this.entries_),
      16); // Tried to set a value for a key that is used already
    var entry = /** @type {Entry} */ ({
      key_: key,
      newer: null,
      older: this.newest_,
      value_: value
    });
    if (!this.newest_) {
      this.oldest_ = entry;
    } else {
      this.newest_.newer = entry;
    }
    this.newest_ = entry;
    this.entries_[key] = entry;
    ++this.count_;
  };


  /**
   * Set a maximum number of entries for the cache.
   * @param {number} size Cache size.
   * @api
   */
  LRUCache.prototype.setSize = function setSize (size) {
    this.highWaterMark = size;
  };


  /**
   * Prune the cache.
   */
  LRUCache.prototype.prune = function prune () {
    while (this.canExpireCache()) {
      this.pop();
    }
  };

  return LRUCache;
}(_events_Target_js__WEBPACK_IMPORTED_MODULE_2__["default"]));

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LRUCache);

//# sourceMappingURL=LRUCache.js.map

/***/ }),

/***/ "./node_modules/ol/structs/RBush.js":
/*!******************************************!*\
  !*** ./node_modules/ol/structs/RBush.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../util.js */ "./node_modules/ol/util.js");
/* harmony import */ var rbush__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! rbush */ "./node_modules/rbush/index.js");
/* harmony import */ var rbush__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(rbush__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _extent_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../extent.js */ "./node_modules/ol/extent.js");
/* harmony import */ var _obj_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../obj.js */ "./node_modules/ol/obj.js");
/**
 * @module ol/structs/RBush
 */





/**
 * @typedef {Object} Entry
 * @property {number} minX
 * @property {number} minY
 * @property {number} maxX
 * @property {number} maxY
 * @property {Object} [value]
 */

/**
 * @classdesc
 * Wrapper around the RBush by Vladimir Agafonkin.
 * See https://github.com/mourner/rbush.
 *
 * @template T
 */
var RBush = function RBush(opt_maxEntries) {

  /**
   * @private
   */
  this.rbush_ = rbush__WEBPACK_IMPORTED_MODULE_0___default()(opt_maxEntries, undefined);

  /**
   * A mapping between the objects added to this rbush wrapper
   * and the objects that are actually added to the internal rbush.
   * @private
   * @type {Object<string, Entry>}
   */
  this.items_ = {};

};

/**
 * Insert a value into the RBush.
 * @param {import("../extent.js").Extent} extent Extent.
 * @param {T} value Value.
 */
RBush.prototype.insert = function insert (extent, value) {
  /** @type {Entry} */
  var item = {
    minX: extent[0],
    minY: extent[1],
    maxX: extent[2],
    maxY: extent[3],
    value: value
  };

  this.rbush_.insert(item);
  this.items_[(0,_util_js__WEBPACK_IMPORTED_MODULE_1__.getUid)(value)] = item;
};


/**
 * Bulk-insert values into the RBush.
 * @param {Array<import("../extent.js").Extent>} extents Extents.
 * @param {Array<T>} values Values.
 */
RBush.prototype.load = function load (extents, values) {
  var items = new Array(values.length);
  for (var i = 0, l = values.length; i < l; i++) {
    var extent = extents[i];
    var value = values[i];

    /** @type {Entry} */
    var item = {
      minX: extent[0],
      minY: extent[1],
      maxX: extent[2],
      maxY: extent[3],
      value: value
    };
    items[i] = item;
    this.items_[(0,_util_js__WEBPACK_IMPORTED_MODULE_1__.getUid)(value)] = item;
  }
  this.rbush_.load(items);
};


/**
 * Remove a value from the RBush.
 * @param {T} value Value.
 * @return {boolean} Removed.
 */
RBush.prototype.remove = function remove (value) {
  var uid = (0,_util_js__WEBPACK_IMPORTED_MODULE_1__.getUid)(value);

  // get the object in which the value was wrapped when adding to the
  // internal rbush. then use that object to do the removal.
  var item = this.items_[uid];
  delete this.items_[uid];
  return this.rbush_.remove(item) !== null;
};


/**
 * Update the extent of a value in the RBush.
 * @param {import("../extent.js").Extent} extent Extent.
 * @param {T} value Value.
 */
RBush.prototype.update = function update (extent, value) {
  var item = this.items_[(0,_util_js__WEBPACK_IMPORTED_MODULE_1__.getUid)(value)];
  var bbox = [item.minX, item.minY, item.maxX, item.maxY];
  if (!(0,_extent_js__WEBPACK_IMPORTED_MODULE_2__.equals)(bbox, extent)) {
    this.remove(value);
    this.insert(extent, value);
  }
};


/**
 * Return all values in the RBush.
 * @return {Array<T>} All.
 */
RBush.prototype.getAll = function getAll () {
  var items = this.rbush_.all();
  return items.map(function(item) {
    return item.value;
  });
};


/**
 * Return all values in the given extent.
 * @param {import("../extent.js").Extent} extent Extent.
 * @return {Array<T>} All in extent.
 */
RBush.prototype.getInExtent = function getInExtent (extent) {
  /** @type {Entry} */
  var bbox = {
    minX: extent[0],
    minY: extent[1],
    maxX: extent[2],
    maxY: extent[3]
  };
  var items = this.rbush_.search(bbox);
  return items.map(function(item) {
    return item.value;
  });
};


/**
 * Calls a callback function with each value in the tree.
 * If the callback returns a truthy value, this value is returned without
 * checking the rest of the tree.
 * @param {function(this: S, T): *} callback Callback.
 * @param {S=} opt_this The object to use as `this` in `callback`.
 * @return {*} Callback return value.
 * @template S
 */
RBush.prototype.forEach = function forEach (callback, opt_this) {
  return this.forEach_(this.getAll(), callback, opt_this);
};


/**
 * Calls a callback function with each value in the provided extent.
 * @param {import("../extent.js").Extent} extent Extent.
 * @param {function(this: S, T): *} callback Callback.
 * @param {S=} opt_this The object to use as `this` in `callback`.
 * @return {*} Callback return value.
 * @template S
 */
RBush.prototype.forEachInExtent = function forEachInExtent (extent, callback, opt_this) {
  return this.forEach_(this.getInExtent(extent), callback, opt_this);
};


/**
 * @param {Array<T>} values Values.
 * @param {function(this: S, T): *} callback Callback.
 * @param {S=} opt_this The object to use as `this` in `callback`.
 * @private
 * @return {*} Callback return value.
 * @template S
 */
RBush.prototype.forEach_ = function forEach_ (values, callback, opt_this) {
  var result;
  for (var i = 0, l = values.length; i < l; i++) {
    result = callback.call(opt_this, values[i]);
    if (result) {
      return result;
    }
  }
  return result;
};


/**
 * @return {boolean} Is empty.
 */
RBush.prototype.isEmpty = function isEmpty$1 () {
  return (0,_obj_js__WEBPACK_IMPORTED_MODULE_3__.isEmpty)(this.items_);
};


/**
 * Remove all values from the RBush.
 */
RBush.prototype.clear = function clear () {
  this.rbush_.clear();
  this.items_ = {};
};


/**
 * @param {import("../extent.js").Extent=} opt_extent Extent.
 * @return {import("../extent.js").Extent} Extent.
 */
RBush.prototype.getExtent = function getExtent (opt_extent) {
  var data = this.rbush_.toJSON();
  return (0,_extent_js__WEBPACK_IMPORTED_MODULE_2__.createOrUpdate)(data.minX, data.minY, data.maxX, data.maxY, opt_extent);
};


/**
 * @param {RBush} rbush R-Tree.
 */
RBush.prototype.concat = function concat (rbush) {
  this.rbush_.load(rbush.rbush_.all());
  for (var i in rbush.items_) {
    this.items_[i] = rbush.items_[i];
  }
};


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (RBush);

//# sourceMappingURL=RBush.js.map

/***/ }),

/***/ "./node_modules/ol/style/Circle.js":
/*!*****************************************!*\
  !*** ./node_modules/ol/style/Circle.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _RegularShape_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RegularShape.js */ "./node_modules/ol/style/RegularShape.js");
/**
 * @module ol/style/Circle
 */




/**
 * @typedef {Object} Options
 * @property {import("./Fill.js").default} [fill] Fill style.
 * @property {number} radius Circle radius.
 * @property {import("./Stroke.js").default} [stroke] Stroke style.
 * @property {import("./AtlasManager.js").default} [atlasManager] The atlas manager to use for this circle.
 * When using WebGL it is recommended to use an atlas manager to avoid texture switching. If an atlas manager is given,
 * the circle is added to an atlas. By default no atlas manager is used.
 */


/**
 * @classdesc
 * Set circle style for vector features.
 * @api
 */
var CircleStyle = /*@__PURE__*/(function (RegularShape) {
  function CircleStyle(opt_options) {

    var options = opt_options || /** @type {Options} */ ({});

    RegularShape.call(this, {
      points: Infinity,
      fill: options.fill,
      radius: options.radius,
      stroke: options.stroke,
      atlasManager: options.atlasManager
    });

  }

  if ( RegularShape ) CircleStyle.__proto__ = RegularShape;
  CircleStyle.prototype = Object.create( RegularShape && RegularShape.prototype );
  CircleStyle.prototype.constructor = CircleStyle;

  /**
  * Clones the style.  If an atlasmanager was provided to the original style it will be used in the cloned style, too.
  * @return {CircleStyle} The cloned style.
  * @override
  * @api
  */
  CircleStyle.prototype.clone = function clone () {
    var style = new CircleStyle({
      fill: this.getFill() ? this.getFill().clone() : undefined,
      stroke: this.getStroke() ? this.getStroke().clone() : undefined,
      radius: this.getRadius(),
      atlasManager: this.atlasManager_
    });
    style.setOpacity(this.getOpacity());
    style.setScale(this.getScale());
    return style;
  };

  /**
  * Set the circle radius.
  *
  * @param {number} radius Circle radius.
  * @api
  */
  CircleStyle.prototype.setRadius = function setRadius (radius) {
    this.radius_ = radius;
    this.render_(this.atlasManager_);
  };

  return CircleStyle;
}(_RegularShape_js__WEBPACK_IMPORTED_MODULE_0__["default"]));


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CircleStyle);

//# sourceMappingURL=Circle.js.map

/***/ }),

/***/ "./node_modules/ol/style/Fill.js":
/*!***************************************!*\
  !*** ./node_modules/ol/style/Fill.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../util.js */ "./node_modules/ol/util.js");
/* harmony import */ var _color_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../color.js */ "./node_modules/ol/color.js");
/**
 * @module ol/style/Fill
 */




/**
 * @typedef {Object} Options
 * @property {import("../color.js").Color|import("../colorlike.js").ColorLike} [color] A color, gradient or pattern.
 * See {@link module:ol/color~Color} and {@link module:ol/colorlike~ColorLike} for possible formats.
 * Default null; if null, the Canvas/renderer default black will be used.
 */


/**
 * @classdesc
 * Set fill style for vector features.
 * @api
 */
var Fill = function Fill(opt_options) {

  var options = opt_options || {};

  /**
   * @private
   * @type {import("../color.js").Color|import("../colorlike.js").ColorLike}
   */
  this.color_ = options.color !== undefined ? options.color : null;

  /**
   * @private
   * @type {string|undefined}
   */
  this.checksum_ = undefined;
};

/**
 * Clones the style. The color is not cloned if it is an {@link module:ol/colorlike~ColorLike}.
 * @return {Fill} The cloned style.
 * @api
 */
Fill.prototype.clone = function clone () {
  var color = this.getColor();
  return new Fill({
    color: Array.isArray(color) ? color.slice() : color || undefined
  });
};

/**
 * Get the fill color.
 * @return {import("../color.js").Color|import("../colorlike.js").ColorLike} Color.
 * @api
 */
Fill.prototype.getColor = function getColor () {
  return this.color_;
};

/**
 * Set the color.
 *
 * @param {import("../color.js").Color|import("../colorlike.js").ColorLike} color Color.
 * @api
 */
Fill.prototype.setColor = function setColor (color) {
  this.color_ = color;
  this.checksum_ = undefined;
};

/**
 * @return {string} The checksum.
 */
Fill.prototype.getChecksum = function getChecksum () {
  if (this.checksum_ === undefined) {
    var color = this.color_;
    if (color) {
      if (Array.isArray(color) || typeof color == 'string') {
        this.checksum_ = 'f' + (0,_color_js__WEBPACK_IMPORTED_MODULE_0__.asString)(/** @type {import("../color.js").Color|string} */ (color));
      } else {
        this.checksum_ = (0,_util_js__WEBPACK_IMPORTED_MODULE_1__.getUid)(this.color_);
      }
    } else {
      this.checksum_ = 'f-';
    }
  }

  return this.checksum_;
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Fill);

//# sourceMappingURL=Fill.js.map

/***/ }),

/***/ "./node_modules/ol/style/Image.js":
/*!****************************************!*\
  !*** ./node_modules/ol/style/Image.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../util.js */ "./node_modules/ol/util.js");
/**
 * @module ol/style/Image
 */



/**
 * @typedef {Object} Options
 * @property {number} opacity
 * @property {boolean} rotateWithView
 * @property {number} rotation
 * @property {number} scale
 */


/**
 * @classdesc
 * A base class used for creating subclasses and not instantiated in
 * apps. Base class for {@link module:ol/style/Icon~Icon}, {@link module:ol/style/Circle~CircleStyle} and
 * {@link module:ol/style/RegularShape~RegularShape}.
 * @abstract
 * @api
 */
var ImageStyle = function ImageStyle(options) {

  /**
   * @private
   * @type {number}
   */
  this.opacity_ = options.opacity;

  /**
   * @private
   * @type {boolean}
   */
  this.rotateWithView_ = options.rotateWithView;

  /**
   * @private
   * @type {number}
   */
  this.rotation_ = options.rotation;

  /**
   * @private
   * @type {number}
   */
  this.scale_ = options.scale;

};

/**
 * Clones the style.
 * @return {ImageStyle} The cloned style.
 * @api
 */
ImageStyle.prototype.clone = function clone () {
  return new ImageStyle({
    opacity: this.getOpacity(),
    scale: this.getScale(),
    rotation: this.getRotation(),
    rotateWithView: this.getRotateWithView()
  });
};

/**
 * Get the symbolizer opacity.
 * @return {number} Opacity.
 * @api
 */
ImageStyle.prototype.getOpacity = function getOpacity () {
  return this.opacity_;
};

/**
 * Determine whether the symbolizer rotates with the map.
 * @return {boolean} Rotate with map.
 * @api
 */
ImageStyle.prototype.getRotateWithView = function getRotateWithView () {
  return this.rotateWithView_;
};

/**
 * Get the symoblizer rotation.
 * @return {number} Rotation.
 * @api
 */
ImageStyle.prototype.getRotation = function getRotation () {
  return this.rotation_;
};

/**
 * Get the symbolizer scale.
 * @return {number} Scale.
 * @api
 */
ImageStyle.prototype.getScale = function getScale () {
  return this.scale_;
};

/**
 * This method is deprecated and always returns false.
 * @return {boolean} false.
 * @deprecated
 * @api
 */
ImageStyle.prototype.getSnapToPixel = function getSnapToPixel () {
  return false;
};

/**
 * Get the anchor point in pixels. The anchor determines the center point for the
 * symbolizer.
 * @abstract
 * @return {Array<number>} Anchor.
 */
ImageStyle.prototype.getAnchor = function getAnchor () {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * Get the image element for the symbolizer.
 * @abstract
 * @param {number} pixelRatio Pixel ratio.
 * @return {HTMLCanvasElement|HTMLVideoElement|HTMLImageElement} Image element.
 */
ImageStyle.prototype.getImage = function getImage (pixelRatio) {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * @abstract
 * @param {number} pixelRatio Pixel ratio.
 * @return {HTMLCanvasElement|HTMLVideoElement|HTMLImageElement} Image element.
 */
ImageStyle.prototype.getHitDetectionImage = function getHitDetectionImage (pixelRatio) {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * @abstract
 * @return {import("../ImageState.js").default} Image state.
 */
ImageStyle.prototype.getImageState = function getImageState () {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * @abstract
 * @return {import("../size.js").Size} Image size.
 */
ImageStyle.prototype.getImageSize = function getImageSize () {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * @abstract
 * @return {import("../size.js").Size} Size of the hit-detection image.
 */
ImageStyle.prototype.getHitDetectionImageSize = function getHitDetectionImageSize () {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * Get the origin of the symbolizer.
 * @abstract
 * @return {Array<number>} Origin.
 */
ImageStyle.prototype.getOrigin = function getOrigin () {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * Get the size of the symbolizer (in pixels).
 * @abstract
 * @return {import("../size.js").Size} Size.
 */
ImageStyle.prototype.getSize = function getSize () {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * Set the opacity.
 *
 * @param {number} opacity Opacity.
 * @api
 */
ImageStyle.prototype.setOpacity = function setOpacity (opacity) {
  this.opacity_ = opacity;
};

/**
 * Set whether to rotate the style with the view.
 *
 * @param {boolean} rotateWithView Rotate with map.
 * @api
 */
ImageStyle.prototype.setRotateWithView = function setRotateWithView (rotateWithView) {
  this.rotateWithView_ = rotateWithView;
};

/**
 * Set the rotation.
 *
 * @param {number} rotation Rotation.
 * @api
 */
ImageStyle.prototype.setRotation = function setRotation (rotation) {
  this.rotation_ = rotation;
};
/**
 * Set the scale.
 *
 * @param {number} scale Scale.
 * @api
 */
ImageStyle.prototype.setScale = function setScale (scale) {
  this.scale_ = scale;
};

/**
 * This method is deprecated and does nothing.
 * @param {boolean} snapToPixel Snap to pixel?
 * @deprecated
 * @api
 */
ImageStyle.prototype.setSnapToPixel = function setSnapToPixel (snapToPixel) {};

/**
 * @abstract
 * @param {function(this: T, import("../events/Event.js").default)} listener Listener function.
 * @param {T} thisArg Value to use as `this` when executing `listener`.
 * @return {import("../events.js").EventsKey|undefined} Listener key.
 * @template T
 */
ImageStyle.prototype.listenImageChange = function listenImageChange (listener, thisArg) {
  return (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * Load not yet loaded URI.
 * @abstract
 */
ImageStyle.prototype.load = function load () {
  (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/**
 * @abstract
 * @param {function(this: T, import("../events/Event.js").default)} listener Listener function.
 * @param {T} thisArg Value to use as `this` when executing `listener`.
 * @template T
 */
ImageStyle.prototype.unlistenImageChange = function unlistenImageChange (listener, thisArg) {
  (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.abstract)();
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ImageStyle);

//# sourceMappingURL=Image.js.map

/***/ }),

/***/ "./node_modules/ol/style/RegularShape.js":
/*!***********************************************!*\
  !*** ./node_modules/ol/style/RegularShape.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _color_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../color.js */ "./node_modules/ol/color.js");
/* harmony import */ var _colorlike_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../colorlike.js */ "./node_modules/ol/colorlike.js");
/* harmony import */ var _dom_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../dom.js */ "./node_modules/ol/dom.js");
/* harmony import */ var _has_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../has.js */ "./node_modules/ol/has.js");
/* harmony import */ var _ImageState_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../ImageState.js */ "./node_modules/ol/ImageState.js");
/* harmony import */ var _render_canvas_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../render/canvas.js */ "./node_modules/ol/render/canvas.js");
/* harmony import */ var _Image_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./Image.js */ "./node_modules/ol/style/Image.js");
/**
 * @module ol/style/RegularShape
 */










/**
 * Specify radius for regular polygons, or radius1 and radius2 for stars.
 * @typedef {Object} Options
 * @property {import("./Fill.js").default} [fill] Fill style.
 * @property {number} points Number of points for stars and regular polygons. In case of a polygon, the number of points
 * is the number of sides.
 * @property {number} [radius] Radius of a regular polygon.
 * @property {number} [radius1] Outer radius of a star.
 * @property {number} [radius2] Inner radius of a star.
 * @property {number} [angle=0] Shape's angle in radians. A value of 0 will have one of the shape's point facing up.
 * @property {import("./Stroke.js").default} [stroke] Stroke style.
 * @property {number} [rotation=0] Rotation in radians (positive rotation clockwise).
 * @property {boolean} [rotateWithView=false] Whether to rotate the shape with the view.
 * @property {import("./AtlasManager.js").default} [atlasManager] The atlas manager to use for this symbol. When
 * using WebGL it is recommended to use an atlas manager to avoid texture switching. If an atlas manager is given, the
 * symbol is added to an atlas. By default no atlas manager is used.
 */


/**
 * @typedef {Object} RenderOptions
 * @property {import("../colorlike.js").ColorLike} [strokeStyle]
 * @property {number} strokeWidth
 * @property {number} size
 * @property {string} lineCap
 * @property {Array<number>} lineDash
 * @property {number} lineDashOffset
 * @property {string} lineJoin
 * @property {number} miterLimit
 */


/**
 * @classdesc
 * Set regular shape style for vector features. The resulting shape will be
 * a regular polygon when `radius` is provided, or a star when `radius1` and
 * `radius2` are provided.
 * @api
 */
var RegularShape = /*@__PURE__*/(function (ImageStyle) {
  function RegularShape(options) {
    /**
     * @type {boolean}
     */
    var rotateWithView = options.rotateWithView !== undefined ?
      options.rotateWithView : false;

    ImageStyle.call(this, {
      opacity: 1,
      rotateWithView: rotateWithView,
      rotation: options.rotation !== undefined ? options.rotation : 0,
      scale: 1
    });

    /**
     * @private
     * @type {Array<string|number>}
     */
    this.checksums_ = null;

    /**
     * @private
     * @type {HTMLCanvasElement}
     */
    this.canvas_ = null;

    /**
     * @private
     * @type {HTMLCanvasElement}
     */
    this.hitDetectionCanvas_ = null;

    /**
     * @private
     * @type {import("./Fill.js").default}
     */
    this.fill_ = options.fill !== undefined ? options.fill : null;

    /**
     * @private
     * @type {Array<number>}
     */
    this.origin_ = [0, 0];

    /**
     * @private
     * @type {number}
     */
    this.points_ = options.points;

    /**
     * @protected
     * @type {number}
     */
    this.radius_ = /** @type {number} */ (options.radius !== undefined ?
      options.radius : options.radius1);

    /**
     * @private
     * @type {number|undefined}
     */
    this.radius2_ = options.radius2;

    /**
     * @private
     * @type {number}
     */
    this.angle_ = options.angle !== undefined ? options.angle : 0;

    /**
     * @private
     * @type {import("./Stroke.js").default}
     */
    this.stroke_ = options.stroke !== undefined ? options.stroke : null;

    /**
     * @private
     * @type {Array<number>}
     */
    this.anchor_ = null;

    /**
     * @private
     * @type {import("../size.js").Size}
     */
    this.size_ = null;

    /**
     * @private
     * @type {import("../size.js").Size}
     */
    this.imageSize_ = null;

    /**
     * @private
     * @type {import("../size.js").Size}
     */
    this.hitDetectionImageSize_ = null;

    /**
     * @protected
     * @type {import("./AtlasManager.js").default|undefined}
     */
    this.atlasManager_ = options.atlasManager;

    this.render_(this.atlasManager_);

  }

  if ( ImageStyle ) RegularShape.__proto__ = ImageStyle;
  RegularShape.prototype = Object.create( ImageStyle && ImageStyle.prototype );
  RegularShape.prototype.constructor = RegularShape;

  /**
   * Clones the style. If an atlasmanager was provided to the original style it will be used in the cloned style, too.
   * @return {RegularShape} The cloned style.
   * @api
   */
  RegularShape.prototype.clone = function clone () {
    var style = new RegularShape({
      fill: this.getFill() ? this.getFill().clone() : undefined,
      points: this.getPoints(),
      radius: this.getRadius(),
      radius2: this.getRadius2(),
      angle: this.getAngle(),
      stroke: this.getStroke() ? this.getStroke().clone() : undefined,
      rotation: this.getRotation(),
      rotateWithView: this.getRotateWithView(),
      atlasManager: this.atlasManager_
    });
    style.setOpacity(this.getOpacity());
    style.setScale(this.getScale());
    return style;
  };

  /**
   * @inheritDoc
   * @api
   */
  RegularShape.prototype.getAnchor = function getAnchor () {
    return this.anchor_;
  };

  /**
   * Get the angle used in generating the shape.
   * @return {number} Shape's rotation in radians.
   * @api
   */
  RegularShape.prototype.getAngle = function getAngle () {
    return this.angle_;
  };

  /**
   * Get the fill style for the shape.
   * @return {import("./Fill.js").default} Fill style.
   * @api
   */
  RegularShape.prototype.getFill = function getFill () {
    return this.fill_;
  };

  /**
   * @inheritDoc
   */
  RegularShape.prototype.getHitDetectionImage = function getHitDetectionImage (pixelRatio) {
    return this.hitDetectionCanvas_;
  };

  /**
   * @inheritDoc
   * @api
   */
  RegularShape.prototype.getImage = function getImage (pixelRatio) {
    return this.canvas_;
  };

  /**
   * @inheritDoc
   */
  RegularShape.prototype.getImageSize = function getImageSize () {
    return this.imageSize_;
  };

  /**
   * @inheritDoc
   */
  RegularShape.prototype.getHitDetectionImageSize = function getHitDetectionImageSize () {
    return this.hitDetectionImageSize_;
  };

  /**
   * @inheritDoc
   */
  RegularShape.prototype.getImageState = function getImageState () {
    return _ImageState_js__WEBPACK_IMPORTED_MODULE_0__["default"].LOADED;
  };

  /**
   * @inheritDoc
   * @api
   */
  RegularShape.prototype.getOrigin = function getOrigin () {
    return this.origin_;
  };

  /**
   * Get the number of points for generating the shape.
   * @return {number} Number of points for stars and regular polygons.
   * @api
   */
  RegularShape.prototype.getPoints = function getPoints () {
    return this.points_;
  };

  /**
   * Get the (primary) radius for the shape.
   * @return {number} Radius.
   * @api
   */
  RegularShape.prototype.getRadius = function getRadius () {
    return this.radius_;
  };

  /**
   * Get the secondary radius for the shape.
   * @return {number|undefined} Radius2.
   * @api
   */
  RegularShape.prototype.getRadius2 = function getRadius2 () {
    return this.radius2_;
  };

  /**
   * @inheritDoc
   * @api
   */
  RegularShape.prototype.getSize = function getSize () {
    return this.size_;
  };

  /**
   * Get the stroke style for the shape.
   * @return {import("./Stroke.js").default} Stroke style.
   * @api
   */
  RegularShape.prototype.getStroke = function getStroke () {
    return this.stroke_;
  };

  /**
   * @inheritDoc
   */
  RegularShape.prototype.listenImageChange = function listenImageChange (listener, thisArg) {
    return undefined;
  };

  /**
   * @inheritDoc
   */
  RegularShape.prototype.load = function load () {};

  /**
   * @inheritDoc
   */
  RegularShape.prototype.unlistenImageChange = function unlistenImageChange (listener, thisArg) {};

  /**
   * @protected
   * @param {import("./AtlasManager.js").default|undefined} atlasManager An atlas manager.
   */
  RegularShape.prototype.render_ = function render_ (atlasManager) {
    var imageSize;
    var lineCap = '';
    var lineJoin = '';
    var miterLimit = 0;
    var lineDash = null;
    var lineDashOffset = 0;
    var strokeStyle;
    var strokeWidth = 0;

    if (this.stroke_) {
      strokeStyle = this.stroke_.getColor();
      if (strokeStyle === null) {
        strokeStyle = _render_canvas_js__WEBPACK_IMPORTED_MODULE_1__.defaultStrokeStyle;
      }
      strokeStyle = (0,_colorlike_js__WEBPACK_IMPORTED_MODULE_2__.asColorLike)(strokeStyle);
      strokeWidth = this.stroke_.getWidth();
      if (strokeWidth === undefined) {
        strokeWidth = _render_canvas_js__WEBPACK_IMPORTED_MODULE_1__.defaultLineWidth;
      }
      lineDash = this.stroke_.getLineDash();
      lineDashOffset = this.stroke_.getLineDashOffset();
      if (!_has_js__WEBPACK_IMPORTED_MODULE_3__.CANVAS_LINE_DASH) {
        lineDash = null;
        lineDashOffset = 0;
      }
      lineJoin = this.stroke_.getLineJoin();
      if (lineJoin === undefined) {
        lineJoin = _render_canvas_js__WEBPACK_IMPORTED_MODULE_1__.defaultLineJoin;
      }
      lineCap = this.stroke_.getLineCap();
      if (lineCap === undefined) {
        lineCap = _render_canvas_js__WEBPACK_IMPORTED_MODULE_1__.defaultLineCap;
      }
      miterLimit = this.stroke_.getMiterLimit();
      if (miterLimit === undefined) {
        miterLimit = _render_canvas_js__WEBPACK_IMPORTED_MODULE_1__.defaultMiterLimit;
      }
    }

    var size = 2 * (this.radius_ + strokeWidth) + 1;

    /** @type {RenderOptions} */
    var renderOptions = {
      strokeStyle: strokeStyle,
      strokeWidth: strokeWidth,
      size: size,
      lineCap: lineCap,
      lineDash: lineDash,
      lineDashOffset: lineDashOffset,
      lineJoin: lineJoin,
      miterLimit: miterLimit
    };

    if (atlasManager === undefined) {
      // no atlas manager is used, create a new canvas
      var context = (0,_dom_js__WEBPACK_IMPORTED_MODULE_4__.createCanvasContext2D)(size, size);
      this.canvas_ = context.canvas;

      // canvas.width and height are rounded to the closest integer
      size = this.canvas_.width;
      imageSize = size;

      this.draw_(renderOptions, context, 0, 0);

      this.createHitDetectionCanvas_(renderOptions);
    } else {
      // an atlas manager is used, add the symbol to an atlas
      size = Math.round(size);

      var hasCustomHitDetectionImage = !this.fill_;
      var renderHitDetectionCallback;
      if (hasCustomHitDetectionImage) {
        // render the hit-detection image into a separate atlas image
        renderHitDetectionCallback =
            this.drawHitDetectionCanvas_.bind(this, renderOptions);
      }

      var id = this.getChecksum();
      var info = atlasManager.add(
        id, size, size, this.draw_.bind(this, renderOptions),
        renderHitDetectionCallback);

      this.canvas_ = info.image;
      this.origin_ = [info.offsetX, info.offsetY];
      imageSize = info.image.width;

      if (hasCustomHitDetectionImage) {
        this.hitDetectionCanvas_ = info.hitImage;
        this.hitDetectionImageSize_ =
            [info.hitImage.width, info.hitImage.height];
      } else {
        this.hitDetectionCanvas_ = this.canvas_;
        this.hitDetectionImageSize_ = [imageSize, imageSize];
      }
    }

    this.anchor_ = [size / 2, size / 2];
    this.size_ = [size, size];
    this.imageSize_ = [imageSize, imageSize];
  };

  /**
   * @private
   * @param {RenderOptions} renderOptions Render options.
   * @param {CanvasRenderingContext2D} context The rendering context.
   * @param {number} x The origin for the symbol (x).
   * @param {number} y The origin for the symbol (y).
   */
  RegularShape.prototype.draw_ = function draw_ (renderOptions, context, x, y) {
    var i, angle0, radiusC;
    // reset transform
    context.setTransform(1, 0, 0, 1, 0, 0);

    // then move to (x, y)
    context.translate(x, y);

    context.beginPath();

    var points = this.points_;
    if (points === Infinity) {
      context.arc(
        renderOptions.size / 2, renderOptions.size / 2,
        this.radius_, 0, 2 * Math.PI, true);
    } else {
      var radius2 = (this.radius2_ !== undefined) ? this.radius2_
        : this.radius_;
      if (radius2 !== this.radius_) {
        points = 2 * points;
      }
      for (i = 0; i <= points; i++) {
        angle0 = i * 2 * Math.PI / points - Math.PI / 2 + this.angle_;
        radiusC = i % 2 === 0 ? this.radius_ : radius2;
        context.lineTo(renderOptions.size / 2 + radiusC * Math.cos(angle0),
          renderOptions.size / 2 + radiusC * Math.sin(angle0));
      }
    }


    if (this.fill_) {
      var color = this.fill_.getColor();
      if (color === null) {
        color = _render_canvas_js__WEBPACK_IMPORTED_MODULE_1__.defaultFillStyle;
      }
      context.fillStyle = (0,_colorlike_js__WEBPACK_IMPORTED_MODULE_2__.asColorLike)(color);
      context.fill();
    }
    if (this.stroke_) {
      context.strokeStyle = renderOptions.strokeStyle;
      context.lineWidth = renderOptions.strokeWidth;
      if (renderOptions.lineDash) {
        context.setLineDash(renderOptions.lineDash);
        context.lineDashOffset = renderOptions.lineDashOffset;
      }
      context.lineCap = /** @type {CanvasLineCap} */ (renderOptions.lineCap);
      context.lineJoin = /** @type {CanvasLineJoin} */ (renderOptions.lineJoin);
      context.miterLimit = renderOptions.miterLimit;
      context.stroke();
    }
    context.closePath();
  };

  /**
   * @private
   * @param {RenderOptions} renderOptions Render options.
   */
  RegularShape.prototype.createHitDetectionCanvas_ = function createHitDetectionCanvas_ (renderOptions) {
    this.hitDetectionImageSize_ = [renderOptions.size, renderOptions.size];
    if (this.fill_) {
      this.hitDetectionCanvas_ = this.canvas_;
      return;
    }

    // if no fill style is set, create an extra hit-detection image with a
    // default fill style
    var context = (0,_dom_js__WEBPACK_IMPORTED_MODULE_4__.createCanvasContext2D)(renderOptions.size, renderOptions.size);
    this.hitDetectionCanvas_ = context.canvas;

    this.drawHitDetectionCanvas_(renderOptions, context, 0, 0);
  };

  /**
   * @private
   * @param {RenderOptions} renderOptions Render options.
   * @param {CanvasRenderingContext2D} context The context.
   * @param {number} x The origin for the symbol (x).
   * @param {number} y The origin for the symbol (y).
   */
  RegularShape.prototype.drawHitDetectionCanvas_ = function drawHitDetectionCanvas_ (renderOptions, context, x, y) {
    // reset transform
    context.setTransform(1, 0, 0, 1, 0, 0);

    // then move to (x, y)
    context.translate(x, y);

    context.beginPath();

    var points = this.points_;
    if (points === Infinity) {
      context.arc(
        renderOptions.size / 2, renderOptions.size / 2,
        this.radius_, 0, 2 * Math.PI, true);
    } else {
      var radius2 = (this.radius2_ !== undefined) ? this.radius2_
        : this.radius_;
      if (radius2 !== this.radius_) {
        points = 2 * points;
      }
      var i, radiusC, angle0;
      for (i = 0; i <= points; i++) {
        angle0 = i * 2 * Math.PI / points - Math.PI / 2 + this.angle_;
        radiusC = i % 2 === 0 ? this.radius_ : radius2;
        context.lineTo(renderOptions.size / 2 + radiusC * Math.cos(angle0),
          renderOptions.size / 2 + radiusC * Math.sin(angle0));
      }
    }

    context.fillStyle = (0,_color_js__WEBPACK_IMPORTED_MODULE_5__.asString)(_render_canvas_js__WEBPACK_IMPORTED_MODULE_1__.defaultFillStyle);
    context.fill();
    if (this.stroke_) {
      context.strokeStyle = renderOptions.strokeStyle;
      context.lineWidth = renderOptions.strokeWidth;
      if (renderOptions.lineDash) {
        context.setLineDash(renderOptions.lineDash);
        context.lineDashOffset = renderOptions.lineDashOffset;
      }
      context.stroke();
    }
    context.closePath();
  };

  /**
   * @return {string} The checksum.
   */
  RegularShape.prototype.getChecksum = function getChecksum () {
    var strokeChecksum = this.stroke_ ?
      this.stroke_.getChecksum() : '-';
    var fillChecksum = this.fill_ ?
      this.fill_.getChecksum() : '-';

    var recalculate = !this.checksums_ ||
        (strokeChecksum != this.checksums_[1] ||
        fillChecksum != this.checksums_[2] ||
        this.radius_ != this.checksums_[3] ||
        this.radius2_ != this.checksums_[4] ||
        this.angle_ != this.checksums_[5] ||
        this.points_ != this.checksums_[6]);

    if (recalculate) {
      var checksum = 'r' + strokeChecksum + fillChecksum +
          (this.radius_ !== undefined ? this.radius_.toString() : '-') +
          (this.radius2_ !== undefined ? this.radius2_.toString() : '-') +
          (this.angle_ !== undefined ? this.angle_.toString() : '-') +
          (this.points_ !== undefined ? this.points_.toString() : '-');
      this.checksums_ = [checksum, strokeChecksum, fillChecksum,
        this.radius_, this.radius2_, this.angle_, this.points_];
    }

    return /** @type {string} */ (this.checksums_[0]);
  };

  return RegularShape;
}(_Image_js__WEBPACK_IMPORTED_MODULE_6__["default"]));


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (RegularShape);

//# sourceMappingURL=RegularShape.js.map

/***/ }),

/***/ "./node_modules/ol/style/Stroke.js":
/*!*****************************************!*\
  !*** ./node_modules/ol/style/Stroke.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _util_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../util.js */ "./node_modules/ol/util.js");
/**
 * @module ol/style/Stroke
 */



/**
 * @typedef {Object} Options
 * @property {import("../color.js").Color|import("../colorlike.js").ColorLike} [color] A color, gradient or pattern.
 * See {@link module:ol/color~Color} and {@link module:ol/colorlike~ColorLike} for possible formats.
 * Default null; if null, the Canvas/renderer default black will be used.
 * @property {string} [lineCap='round'] Line cap style: `butt`, `round`, or `square`.
 * @property {string} [lineJoin='round'] Line join style: `bevel`, `round`, or `miter`.
 * @property {Array<number>} [lineDash] Line dash pattern. Default is `undefined` (no dash).
 * Please note that Internet Explorer 10 and lower do not support the `setLineDash` method on
 * the `CanvasRenderingContext2D` and therefore this option will have no visual effect in these browsers.
 * @property {number} [lineDashOffset=0] Line dash offset.
 * @property {number} [miterLimit=10] Miter limit.
 * @property {number} [width] Width.
 */


/**
 * @classdesc
 * Set stroke style for vector features.
 * Note that the defaults given are the Canvas defaults, which will be used if
 * option is not defined. The `get` functions return whatever was entered in
 * the options; they will not return the default.
 * @api
 */
var Stroke = function Stroke(opt_options) {

  var options = opt_options || {};

  /**
   * @private
   * @type {import("../color.js").Color|import("../colorlike.js").ColorLike}
   */
  this.color_ = options.color !== undefined ? options.color : null;

  /**
   * @private
   * @type {string|undefined}
   */
  this.lineCap_ = options.lineCap;

  /**
   * @private
   * @type {Array<number>}
   */
  this.lineDash_ = options.lineDash !== undefined ? options.lineDash : null;

  /**
   * @private
   * @type {number|undefined}
   */
  this.lineDashOffset_ = options.lineDashOffset;

  /**
   * @private
   * @type {string|undefined}
   */
  this.lineJoin_ = options.lineJoin;

  /**
   * @private
   * @type {number|undefined}
   */
  this.miterLimit_ = options.miterLimit;

  /**
   * @private
   * @type {number|undefined}
   */
  this.width_ = options.width;

  /**
   * @private
   * @type {string|undefined}
   */
  this.checksum_ = undefined;
};

/**
 * Clones the style.
 * @return {Stroke} The cloned style.
 * @api
 */
Stroke.prototype.clone = function clone () {
  var color = this.getColor();
  return new Stroke({
    color: Array.isArray(color) ? color.slice() : color || undefined,
    lineCap: this.getLineCap(),
    lineDash: this.getLineDash() ? this.getLineDash().slice() : undefined,
    lineDashOffset: this.getLineDashOffset(),
    lineJoin: this.getLineJoin(),
    miterLimit: this.getMiterLimit(),
    width: this.getWidth()
  });
};

/**
 * Get the stroke color.
 * @return {import("../color.js").Color|import("../colorlike.js").ColorLike} Color.
 * @api
 */
Stroke.prototype.getColor = function getColor () {
  return this.color_;
};

/**
 * Get the line cap type for the stroke.
 * @return {string|undefined} Line cap.
 * @api
 */
Stroke.prototype.getLineCap = function getLineCap () {
  return this.lineCap_;
};

/**
 * Get the line dash style for the stroke.
 * @return {Array<number>} Line dash.
 * @api
 */
Stroke.prototype.getLineDash = function getLineDash () {
  return this.lineDash_;
};

/**
 * Get the line dash offset for the stroke.
 * @return {number|undefined} Line dash offset.
 * @api
 */
Stroke.prototype.getLineDashOffset = function getLineDashOffset () {
  return this.lineDashOffset_;
};

/**
 * Get the line join type for the stroke.
 * @return {string|undefined} Line join.
 * @api
 */
Stroke.prototype.getLineJoin = function getLineJoin () {
  return this.lineJoin_;
};

/**
 * Get the miter limit for the stroke.
 * @return {number|undefined} Miter limit.
 * @api
 */
Stroke.prototype.getMiterLimit = function getMiterLimit () {
  return this.miterLimit_;
};

/**
 * Get the stroke width.
 * @return {number|undefined} Width.
 * @api
 */
Stroke.prototype.getWidth = function getWidth () {
  return this.width_;
};

/**
 * Set the color.
 *
 * @param {import("../color.js").Color|import("../colorlike.js").ColorLike} color Color.
 * @api
 */
Stroke.prototype.setColor = function setColor (color) {
  this.color_ = color;
  this.checksum_ = undefined;
};

/**
 * Set the line cap.
 *
 * @param {string|undefined} lineCap Line cap.
 * @api
 */
Stroke.prototype.setLineCap = function setLineCap (lineCap) {
  this.lineCap_ = lineCap;
  this.checksum_ = undefined;
};

/**
 * Set the line dash.
 *
 * Please note that Internet Explorer 10 and lower [do not support][mdn] the
 * `setLineDash` method on the `CanvasRenderingContext2D` and therefore this
 * property will have no visual effect in these browsers.
 *
 * [mdn]: https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/setLineDash#Browser_compatibility
 *
 * @param {Array<number>} lineDash Line dash.
 * @api
 */
Stroke.prototype.setLineDash = function setLineDash (lineDash) {
  this.lineDash_ = lineDash;
  this.checksum_ = undefined;
};

/**
 * Set the line dash offset.
 *
 * @param {number|undefined} lineDashOffset Line dash offset.
 * @api
 */
Stroke.prototype.setLineDashOffset = function setLineDashOffset (lineDashOffset) {
  this.lineDashOffset_ = lineDashOffset;
  this.checksum_ = undefined;
};

/**
 * Set the line join.
 *
 * @param {string|undefined} lineJoin Line join.
 * @api
 */
Stroke.prototype.setLineJoin = function setLineJoin (lineJoin) {
  this.lineJoin_ = lineJoin;
  this.checksum_ = undefined;
};

/**
 * Set the miter limit.
 *
 * @param {number|undefined} miterLimit Miter limit.
 * @api
 */
Stroke.prototype.setMiterLimit = function setMiterLimit (miterLimit) {
  this.miterLimit_ = miterLimit;
  this.checksum_ = undefined;
};

/**
 * Set the width.
 *
 * @param {number|undefined} width Width.
 * @api
 */
Stroke.prototype.setWidth = function setWidth (width) {
  this.width_ = width;
  this.checksum_ = undefined;
};

/**
 * @return {string} The checksum.
 */
Stroke.prototype.getChecksum = function getChecksum () {
  if (this.checksum_ === undefined) {
    this.checksum_ = 's';
    if (this.color_) {
      if (typeof this.color_ === 'string') {
        this.checksum_ += this.color_;
      } else {
        this.checksum_ += (0,_util_js__WEBPACK_IMPORTED_MODULE_0__.getUid)(this.color_);
      }
    } else {
      this.checksum_ += '-';
    }
    this.checksum_ += ',' +
        (this.lineCap_ !== undefined ?
          this.lineCap_.toString() : '-') + ',' +
        (this.lineDash_ ?
          this.lineDash_.toString() : '-') + ',' +
        (this.lineDashOffset_ !== undefined ?
          this.lineDashOffset_ : '-') + ',' +
        (this.lineJoin_ !== undefined ?
          this.lineJoin_ : '-') + ',' +
        (this.miterLimit_ !== undefined ?
          this.miterLimit_.toString() : '-') + ',' +
        (this.width_ !== undefined ?
          this.width_.toString() : '-');
  }

  return this.checksum_;
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Stroke);

//# sourceMappingURL=Stroke.js.map

/***/ }),

/***/ "./node_modules/ol/style/Style.js":
/*!****************************************!*\
  !*** ./node_modules/ol/style/Style.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "toFunction": () => (/* binding */ toFunction),
/* harmony export */   "createDefaultStyle": () => (/* binding */ createDefaultStyle),
/* harmony export */   "createEditingStyle": () => (/* binding */ createEditingStyle),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _asserts_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../asserts.js */ "./node_modules/ol/asserts.js");
/* harmony import */ var _geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../geom/GeometryType.js */ "./node_modules/ol/geom/GeometryType.js");
/* harmony import */ var _Circle_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Circle.js */ "./node_modules/ol/style/Circle.js");
/* harmony import */ var _Fill_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Fill.js */ "./node_modules/ol/style/Fill.js");
/* harmony import */ var _Stroke_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Stroke.js */ "./node_modules/ol/style/Stroke.js");
/**
 * @module ol/style/Style
 */

/**
 * Feature styles.
 *
 * If no style is defined, the following default style is used:
 * ```js
 *  import {Fill, Stroke, Circle, Style} from 'ol/style';
 *
 *  var fill = new Fill({
 *    color: 'rgba(255,255,255,0.4)'
 *  });
 *  var stroke = new Stroke({
 *    color: '#3399CC',
 *    width: 1.25
 *  });
 *  var styles = [
 *    new Style({
 *      image: new Circle({
 *        fill: fill,
 *        stroke: stroke,
 *        radius: 5
 *      }),
 *      fill: fill,
 *      stroke: stroke
 *    })
 *  ];
 * ```
 *
 * A separate editing style has the following defaults:
 * ```js
 *  import {Fill, Stroke, Circle, Style} from 'ol/style';
 *  import GeometryType from 'ol/geom/GeometryType';
 *
 *  var white = [255, 255, 255, 1];
 *  var blue = [0, 153, 255, 1];
 *  var width = 3;
 *  styles[GeometryType.POLYGON] = [
 *    new Style({
 *      fill: new Fill({
 *        color: [255, 255, 255, 0.5]
 *      })
 *    })
 *  ];
 *  styles[GeometryType.MULTI_POLYGON] =
 *      styles[GeometryType.POLYGON];
 *  styles[GeometryType.LINE_STRING] = [
 *    new Style({
 *      stroke: new Stroke({
 *        color: white,
 *        width: width + 2
 *      })
 *    }),
 *    new Style({
 *      stroke: new Stroke({
 *        color: blue,
 *        width: width
 *      })
 *    })
 *  ];
 *  styles[GeometryType.MULTI_LINE_STRING] =
 *      styles[GeometryType.LINE_STRING];
 *  styles[GeometryType.POINT] = [
 *    new Style({
 *      image: new Circle({
 *        radius: width * 2,
 *        fill: new Fill({
 *          color: blue
 *        }),
 *        stroke: new Stroke({
 *          color: white,
 *          width: width / 2
 *        })
 *      }),
 *      zIndex: Infinity
 *    })
 *  ];
 *  styles[GeometryType.MULTI_POINT] =
 *      styles[GeometryType.POINT];
 *  styles[GeometryType.GEOMETRY_COLLECTION] =
 *      styles[GeometryType.POLYGON].concat(
 *          styles[GeometryType.LINE_STRING],
 *          styles[GeometryType.POINT]
 *      );
 * ```
 */







/**
 * A function that takes an {@link module:ol/Feature} and a `{number}`
 * representing the view's resolution. The function should return a
 * {@link module:ol/style/Style} or an array of them. This way e.g. a
 * vector layer can be styled.
 *
 * @typedef {function(import("../Feature.js").FeatureLike, number):(Style|Array<Style>)} StyleFunction
 */

/**
 * A {@link Style}, an array of {@link Style}, or a {@link StyleFunction}.
 * @typedef {Style|Array<Style>|StyleFunction} StyleLike
 */

/**
 * A function that takes an {@link module:ol/Feature} as argument and returns an
 * {@link module:ol/geom/Geometry} that will be rendered and styled for the feature.
 *
 * @typedef {function(import("../Feature.js").FeatureLike):
 *     (import("../geom/Geometry.js").default|import("../render/Feature.js").default|undefined)} GeometryFunction
 */


/**
 * Custom renderer function. Takes two arguments:
 *
 * 1. The pixel coordinates of the geometry in GeoJSON notation.
 * 2. The {@link module:ol/render~State} of the layer renderer.
 *
 * @typedef {function((import("../coordinate.js").Coordinate|Array<import("../coordinate.js").Coordinate>|Array<Array<import("../coordinate.js").Coordinate>>),import("../render.js").State)}
 * RenderFunction
 */


/**
 * @typedef {Object} Options
 * @property {string|import("../geom/Geometry.js").default|GeometryFunction} [geometry] Feature property or geometry
 * or function returning a geometry to render for this style.
 * @property {import("./Fill.js").default} [fill] Fill style.
 * @property {import("./Image.js").default} [image] Image style.
 * @property {RenderFunction} [renderer] Custom renderer. When configured, `fill`, `stroke` and `image` will be
 * ignored, and the provided function will be called with each render frame for each geometry.
 * @property {import("./Stroke.js").default} [stroke] Stroke style.
 * @property {import("./Text.js").default} [text] Text style.
 * @property {number} [zIndex] Z index.
 */

/**
 * @classdesc
 * Container for vector feature rendering styles. Any changes made to the style
 * or its children through `set*()` methods will not take effect until the
 * feature or layer that uses the style is re-rendered.
 * @api
 */
var Style = function Style(opt_options) {

  var options = opt_options || {};

  /**
   * @private
   * @type {string|import("../geom/Geometry.js").default|GeometryFunction}
   */
  this.geometry_ = null;

  /**
   * @private
   * @type {!GeometryFunction}
   */
  this.geometryFunction_ = defaultGeometryFunction;

  if (options.geometry !== undefined) {
    this.setGeometry(options.geometry);
  }

  /**
   * @private
   * @type {import("./Fill.js").default}
   */
  this.fill_ = options.fill !== undefined ? options.fill : null;

  /**
     * @private
     * @type {import("./Image.js").default}
     */
  this.image_ = options.image !== undefined ? options.image : null;

  /**
   * @private
   * @type {RenderFunction|null}
   */
  this.renderer_ = options.renderer !== undefined ? options.renderer : null;

  /**
   * @private
   * @type {import("./Stroke.js").default}
   */
  this.stroke_ = options.stroke !== undefined ? options.stroke : null;

  /**
   * @private
   * @type {import("./Text.js").default}
   */
  this.text_ = options.text !== undefined ? options.text : null;

  /**
   * @private
   * @type {number|undefined}
   */
  this.zIndex_ = options.zIndex;

};

/**
 * Clones the style.
 * @return {Style} The cloned style.
 * @api
 */
Style.prototype.clone = function clone () {
  var geometry = this.getGeometry();
  if (geometry && typeof geometry === 'object') {
    geometry = /** @type {import("../geom/Geometry.js").default} */ (geometry).clone();
  }
  return new Style({
    geometry: geometry,
    fill: this.getFill() ? this.getFill().clone() : undefined,
    image: this.getImage() ? this.getImage().clone() : undefined,
    stroke: this.getStroke() ? this.getStroke().clone() : undefined,
    text: this.getText() ? this.getText().clone() : undefined,
    zIndex: this.getZIndex()
  });
};

/**
 * Get the custom renderer function that was configured with
 * {@link #setRenderer} or the `renderer` constructor option.
 * @return {RenderFunction|null} Custom renderer function.
 * @api
 */
Style.prototype.getRenderer = function getRenderer () {
  return this.renderer_;
};

/**
 * Sets a custom renderer function for this style. When set, `fill`, `stroke`
 * and `image` options of the style will be ignored.
 * @param {RenderFunction|null} renderer Custom renderer function.
 * @api
 */
Style.prototype.setRenderer = function setRenderer (renderer) {
  this.renderer_ = renderer;
};

/**
 * Get the geometry to be rendered.
 * @return {string|import("../geom/Geometry.js").default|GeometryFunction}
 * Feature property or geometry or function that returns the geometry that will
 * be rendered with this style.
 * @api
 */
Style.prototype.getGeometry = function getGeometry () {
  return this.geometry_;
};

/**
 * Get the function used to generate a geometry for rendering.
 * @return {!GeometryFunction} Function that is called with a feature
 * and returns the geometry to render instead of the feature's geometry.
 * @api
 */
Style.prototype.getGeometryFunction = function getGeometryFunction () {
  return this.geometryFunction_;
};

/**
 * Get the fill style.
 * @return {import("./Fill.js").default} Fill style.
 * @api
 */
Style.prototype.getFill = function getFill () {
  return this.fill_;
};

/**
 * Set the fill style.
 * @param {import("./Fill.js").default} fill Fill style.
 * @api
 */
Style.prototype.setFill = function setFill (fill) {
  this.fill_ = fill;
};

/**
 * Get the image style.
 * @return {import("./Image.js").default} Image style.
 * @api
 */
Style.prototype.getImage = function getImage () {
  return this.image_;
};

/**
 * Set the image style.
 * @param {import("./Image.js").default} image Image style.
 * @api
 */
Style.prototype.setImage = function setImage (image) {
  this.image_ = image;
};

/**
 * Get the stroke style.
 * @return {import("./Stroke.js").default} Stroke style.
 * @api
 */
Style.prototype.getStroke = function getStroke () {
  return this.stroke_;
};

/**
 * Set the stroke style.
 * @param {import("./Stroke.js").default} stroke Stroke style.
 * @api
 */
Style.prototype.setStroke = function setStroke (stroke) {
  this.stroke_ = stroke;
};

/**
 * Get the text style.
 * @return {import("./Text.js").default} Text style.
 * @api
 */
Style.prototype.getText = function getText () {
  return this.text_;
};

/**
 * Set the text style.
 * @param {import("./Text.js").default} text Text style.
 * @api
 */
Style.prototype.setText = function setText (text) {
  this.text_ = text;
};

/**
 * Get the z-index for the style.
 * @return {number|undefined} ZIndex.
 * @api
 */
Style.prototype.getZIndex = function getZIndex () {
  return this.zIndex_;
};

/**
 * Set a geometry that is rendered instead of the feature's geometry.
 *
 * @param {string|import("../geom/Geometry.js").default|GeometryFunction} geometry
 *   Feature property or geometry or function returning a geometry to render
 *   for this style.
 * @api
 */
Style.prototype.setGeometry = function setGeometry (geometry) {
  if (typeof geometry === 'function') {
    this.geometryFunction_ = geometry;
  } else if (typeof geometry === 'string') {
    this.geometryFunction_ = function(feature) {
      return (
        /** @type {import("../geom/Geometry.js").default} */ (feature.get(geometry))
      );
    };
  } else if (!geometry) {
    this.geometryFunction_ = defaultGeometryFunction;
  } else if (geometry !== undefined) {
    this.geometryFunction_ = function() {
      return (
        /** @type {import("../geom/Geometry.js").default} */ (geometry)
      );
    };
  }
  this.geometry_ = geometry;
};

/**
 * Set the z-index.
 *
 * @param {number|undefined} zIndex ZIndex.
 * @api
 */
Style.prototype.setZIndex = function setZIndex (zIndex) {
  this.zIndex_ = zIndex;
};


/**
 * Convert the provided object into a style function.  Functions passed through
 * unchanged.  Arrays of Style or single style objects wrapped in a
 * new style function.
 * @param {StyleFunction|Array<Style>|Style} obj
 *     A style function, a single style, or an array of styles.
 * @return {StyleFunction} A style function.
 */
function toFunction(obj) {
  var styleFunction;

  if (typeof obj === 'function') {
    styleFunction = obj;
  } else {
    /**
     * @type {Array<Style>}
     */
    var styles;
    if (Array.isArray(obj)) {
      styles = obj;
    } else {
      (0,_asserts_js__WEBPACK_IMPORTED_MODULE_0__.assert)(typeof /** @type {?} */ (obj).getZIndex === 'function',
        41); // Expected an `Style` or an array of `Style`
      var style = /** @type {Style} */ (obj);
      styles = [style];
    }
    styleFunction = function() {
      return styles;
    };
  }
  return styleFunction;
}


/**
 * @type {Array<Style>}
 */
var defaultStyles = null;


/**
 * @param {import("../Feature.js").FeatureLike} feature Feature.
 * @param {number} resolution Resolution.
 * @return {Array<Style>} Style.
 */
function createDefaultStyle(feature, resolution) {
  // We don't use an immediately-invoked function
  // and a closure so we don't get an error at script evaluation time in
  // browsers that do not support Canvas. (import("./Circle.js").CircleStyle does
  // canvas.getContext('2d') at construction time, which will cause an.error
  // in such browsers.)
  if (!defaultStyles) {
    var fill = new _Fill_js__WEBPACK_IMPORTED_MODULE_1__["default"]({
      color: 'rgba(255,255,255,0.4)'
    });
    var stroke = new _Stroke_js__WEBPACK_IMPORTED_MODULE_2__["default"]({
      color: '#3399CC',
      width: 1.25
    });
    defaultStyles = [
      new Style({
        image: new _Circle_js__WEBPACK_IMPORTED_MODULE_3__["default"]({
          fill: fill,
          stroke: stroke,
          radius: 5
        }),
        fill: fill,
        stroke: stroke
      })
    ];
  }
  return defaultStyles;
}


/**
 * Default styles for editing features.
 * @return {Object<import("../geom/GeometryType.js").default, Array<Style>>} Styles
 */
function createEditingStyle() {
  /** @type {Object<import("../geom/GeometryType.js").default, Array<Style>>} */
  var styles = {};
  var white = [255, 255, 255, 1];
  var blue = [0, 153, 255, 1];
  var width = 3;
  styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].POLYGON] = [
    new Style({
      fill: new _Fill_js__WEBPACK_IMPORTED_MODULE_1__["default"]({
        color: [255, 255, 255, 0.5]
      })
    })
  ];
  styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].MULTI_POLYGON] =
      styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].POLYGON];

  styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].LINE_STRING] = [
    new Style({
      stroke: new _Stroke_js__WEBPACK_IMPORTED_MODULE_2__["default"]({
        color: white,
        width: width + 2
      })
    }),
    new Style({
      stroke: new _Stroke_js__WEBPACK_IMPORTED_MODULE_2__["default"]({
        color: blue,
        width: width
      })
    })
  ];
  styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].MULTI_LINE_STRING] =
      styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].LINE_STRING];

  styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].CIRCLE] =
      styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].POLYGON].concat(
        styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].LINE_STRING]
      );


  styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].POINT] = [
    new Style({
      image: new _Circle_js__WEBPACK_IMPORTED_MODULE_3__["default"]({
        radius: width * 2,
        fill: new _Fill_js__WEBPACK_IMPORTED_MODULE_1__["default"]({
          color: blue
        }),
        stroke: new _Stroke_js__WEBPACK_IMPORTED_MODULE_2__["default"]({
          color: white,
          width: width / 2
        })
      }),
      zIndex: Infinity
    })
  ];
  styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].MULTI_POINT] =
      styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].POINT];

  styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].GEOMETRY_COLLECTION] =
      styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].POLYGON].concat(
        styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].LINE_STRING],
        styles[_geom_GeometryType_js__WEBPACK_IMPORTED_MODULE_4__["default"].POINT]
      );

  return styles;
}


/**
 * Function that is called with a feature and returns its default geometry.
 * @param {import("../Feature.js").FeatureLike} feature Feature to get the geometry for.
 * @return {import("../geom/Geometry.js").default|import("../render/Feature.js").default|undefined} Geometry to render.
 */
function defaultGeometryFunction(feature) {
  return feature.getGeometry();
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Style);

//# sourceMappingURL=Style.js.map

/***/ }),

/***/ "./node_modules/ol/transform.js":
/*!**************************************!*\
  !*** ./node_modules/ol/transform.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "create": () => (/* binding */ create),
/* harmony export */   "reset": () => (/* binding */ reset),
/* harmony export */   "multiply": () => (/* binding */ multiply),
/* harmony export */   "set": () => (/* binding */ set),
/* harmony export */   "setFromArray": () => (/* binding */ setFromArray),
/* harmony export */   "apply": () => (/* binding */ apply),
/* harmony export */   "rotate": () => (/* binding */ rotate),
/* harmony export */   "scale": () => (/* binding */ scale),
/* harmony export */   "translate": () => (/* binding */ translate),
/* harmony export */   "compose": () => (/* binding */ compose),
/* harmony export */   "invert": () => (/* binding */ invert),
/* harmony export */   "determinant": () => (/* binding */ determinant)
/* harmony export */ });
/* harmony import */ var _asserts_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./asserts.js */ "./node_modules/ol/asserts.js");
/**
 * @module ol/transform
 */



/**
 * An array representing an affine 2d transformation for use with
 * {@link module:ol/transform} functions. The array has 6 elements.
 * @typedef {!Array<number>} Transform
 */


/**
 * Collection of affine 2d transformation functions. The functions work on an
 * array of 6 elements. The element order is compatible with the [SVGMatrix
 * interface](https://developer.mozilla.org/en-US/docs/Web/API/SVGMatrix) and is
 * a subset (elements a to f) of a 3×3 matrix:
 * ```
 * [ a c e ]
 * [ b d f ]
 * [ 0 0 1 ]
 * ```
 */


/**
 * @private
 * @type {Transform}
 */
var tmp_ = new Array(6);


/**
 * Create an identity transform.
 * @return {!Transform} Identity transform.
 */
function create() {
  return [1, 0, 0, 1, 0, 0];
}


/**
 * Resets the given transform to an identity transform.
 * @param {!Transform} transform Transform.
 * @return {!Transform} Transform.
 */
function reset(transform) {
  return set(transform, 1, 0, 0, 1, 0, 0);
}


/**
 * Multiply the underlying matrices of two transforms and return the result in
 * the first transform.
 * @param {!Transform} transform1 Transform parameters of matrix 1.
 * @param {!Transform} transform2 Transform parameters of matrix 2.
 * @return {!Transform} transform1 multiplied with transform2.
 */
function multiply(transform1, transform2) {
  var a1 = transform1[0];
  var b1 = transform1[1];
  var c1 = transform1[2];
  var d1 = transform1[3];
  var e1 = transform1[4];
  var f1 = transform1[5];
  var a2 = transform2[0];
  var b2 = transform2[1];
  var c2 = transform2[2];
  var d2 = transform2[3];
  var e2 = transform2[4];
  var f2 = transform2[5];

  transform1[0] = a1 * a2 + c1 * b2;
  transform1[1] = b1 * a2 + d1 * b2;
  transform1[2] = a1 * c2 + c1 * d2;
  transform1[3] = b1 * c2 + d1 * d2;
  transform1[4] = a1 * e2 + c1 * f2 + e1;
  transform1[5] = b1 * e2 + d1 * f2 + f1;

  return transform1;
}

/**
 * Set the transform components a-f on a given transform.
 * @param {!Transform} transform Transform.
 * @param {number} a The a component of the transform.
 * @param {number} b The b component of the transform.
 * @param {number} c The c component of the transform.
 * @param {number} d The d component of the transform.
 * @param {number} e The e component of the transform.
 * @param {number} f The f component of the transform.
 * @return {!Transform} Matrix with transform applied.
 */
function set(transform, a, b, c, d, e, f) {
  transform[0] = a;
  transform[1] = b;
  transform[2] = c;
  transform[3] = d;
  transform[4] = e;
  transform[5] = f;
  return transform;
}


/**
 * Set transform on one matrix from another matrix.
 * @param {!Transform} transform1 Matrix to set transform to.
 * @param {!Transform} transform2 Matrix to set transform from.
 * @return {!Transform} transform1 with transform from transform2 applied.
 */
function setFromArray(transform1, transform2) {
  transform1[0] = transform2[0];
  transform1[1] = transform2[1];
  transform1[2] = transform2[2];
  transform1[3] = transform2[3];
  transform1[4] = transform2[4];
  transform1[5] = transform2[5];
  return transform1;
}


/**
 * Transforms the given coordinate with the given transform returning the
 * resulting, transformed coordinate. The coordinate will be modified in-place.
 *
 * @param {Transform} transform The transformation.
 * @param {import("./coordinate.js").Coordinate|import("./pixel.js").Pixel} coordinate The coordinate to transform.
 * @return {import("./coordinate.js").Coordinate|import("./pixel.js").Pixel} return coordinate so that operations can be
 *     chained together.
 */
function apply(transform, coordinate) {
  var x = coordinate[0];
  var y = coordinate[1];
  coordinate[0] = transform[0] * x + transform[2] * y + transform[4];
  coordinate[1] = transform[1] * x + transform[3] * y + transform[5];
  return coordinate;
}


/**
 * Applies rotation to the given transform.
 * @param {!Transform} transform Transform.
 * @param {number} angle Angle in radians.
 * @return {!Transform} The rotated transform.
 */
function rotate(transform, angle) {
  var cos = Math.cos(angle);
  var sin = Math.sin(angle);
  return multiply(transform, set(tmp_, cos, sin, -sin, cos, 0, 0));
}


/**
 * Applies scale to a given transform.
 * @param {!Transform} transform Transform.
 * @param {number} x Scale factor x.
 * @param {number} y Scale factor y.
 * @return {!Transform} The scaled transform.
 */
function scale(transform, x, y) {
  return multiply(transform, set(tmp_, x, 0, 0, y, 0, 0));
}


/**
 * Applies translation to the given transform.
 * @param {!Transform} transform Transform.
 * @param {number} dx Translation x.
 * @param {number} dy Translation y.
 * @return {!Transform} The translated transform.
 */
function translate(transform, dx, dy) {
  return multiply(transform, set(tmp_, 1, 0, 0, 1, dx, dy));
}


/**
 * Creates a composite transform given an initial translation, scale, rotation, and
 * final translation (in that order only, not commutative).
 * @param {!Transform} transform The transform (will be modified in place).
 * @param {number} dx1 Initial translation x.
 * @param {number} dy1 Initial translation y.
 * @param {number} sx Scale factor x.
 * @param {number} sy Scale factor y.
 * @param {number} angle Rotation (in counter-clockwise radians).
 * @param {number} dx2 Final translation x.
 * @param {number} dy2 Final translation y.
 * @return {!Transform} The composite transform.
 */
function compose(transform, dx1, dy1, sx, sy, angle, dx2, dy2) {
  var sin = Math.sin(angle);
  var cos = Math.cos(angle);
  transform[0] = sx * cos;
  transform[1] = sy * sin;
  transform[2] = -sx * sin;
  transform[3] = sy * cos;
  transform[4] = dx2 * sx * cos - dy2 * sx * sin + dx1;
  transform[5] = dx2 * sy * sin + dy2 * sy * cos + dy1;
  return transform;
}


/**
 * Invert the given transform.
 * @param {!Transform} transform Transform.
 * @return {!Transform} Inverse of the transform.
 */
function invert(transform) {
  var det = determinant(transform);
  (0,_asserts_js__WEBPACK_IMPORTED_MODULE_0__.assert)(det !== 0, 32); // Transformation matrix cannot be inverted

  var a = transform[0];
  var b = transform[1];
  var c = transform[2];
  var d = transform[3];
  var e = transform[4];
  var f = transform[5];

  transform[0] = d / det;
  transform[1] = -b / det;
  transform[2] = -c / det;
  transform[3] = a / det;
  transform[4] = (c * f - d * e) / det;
  transform[5] = -(a * f - b * e) / det;

  return transform;
}


/**
 * Returns the determinant of the given matrix.
 * @param {!Transform} mat Matrix.
 * @return {number} Determinant.
 */
function determinant(mat) {
  return mat[0] * mat[3] - mat[1] * mat[2];
}

//# sourceMappingURL=transform.js.map

/***/ }),

/***/ "./node_modules/ol/util.js":
/*!*********************************!*\
  !*** ./node_modules/ol/util.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "abstract": () => (/* binding */ abstract),
/* harmony export */   "inherits": () => (/* binding */ inherits),
/* harmony export */   "getUid": () => (/* binding */ getUid),
/* harmony export */   "VERSION": () => (/* binding */ VERSION)
/* harmony export */ });
/**
 * @module ol/util
 */

/**
 * @return {?} Any return.
 */
function abstract() {
  return /** @type {?} */ ((function() {
    throw new Error('Unimplemented abstract method.');
  })());
}

/**
 * Inherit the prototype methods from one constructor into another.
 *
 * Usage:
 *
 *     function ParentClass(a, b) { }
 *     ParentClass.prototype.foo = function(a) { }
 *
 *     function ChildClass(a, b, c) {
 *       // Call parent constructor
 *       ParentClass.call(this, a, b);
 *     }
 *     inherits(ChildClass, ParentClass);
 *
 *     var child = new ChildClass('a', 'b', 'see');
 *     child.foo(); // This works.
 *
 * @param {!Function} childCtor Child constructor.
 * @param {!Function} parentCtor Parent constructor.
 * @function module:ol.inherits
 * @deprecated
 * @api
 */
function inherits(childCtor, parentCtor) {
  childCtor.prototype = Object.create(parentCtor.prototype);
  childCtor.prototype.constructor = childCtor;
}

/**
 * Counter for getUid.
 * @type {number}
 * @private
 */
var uidCounter_ = 0;

/**
 * Gets a unique ID for an object. This mutates the object so that further calls
 * with the same object as a parameter returns the same value. Unique IDs are generated
 * as a strictly increasing sequence. Adapted from goog.getUid.
 *
 * @param {Object} obj The object to get the unique ID for.
 * @return {string} The unique ID for the object.
 * @function module:ol.getUid
 * @api
 */
function getUid(obj) {
  return obj.ol_uid || (obj.ol_uid = String(++uidCounter_));
}

/**
 * OpenLayers version.
 * @type {string}
 */
var VERSION = '5.3.3';

//# sourceMappingURL=util.js.map

/***/ }),

/***/ "./node_modules/ol/webgl.js":
/*!**********************************!*\
  !*** ./node_modules/ol/webgl.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ONE": () => (/* binding */ ONE),
/* harmony export */   "SRC_ALPHA": () => (/* binding */ SRC_ALPHA),
/* harmony export */   "COLOR_ATTACHMENT0": () => (/* binding */ COLOR_ATTACHMENT0),
/* harmony export */   "COLOR_BUFFER_BIT": () => (/* binding */ COLOR_BUFFER_BIT),
/* harmony export */   "TRIANGLES": () => (/* binding */ TRIANGLES),
/* harmony export */   "TRIANGLE_STRIP": () => (/* binding */ TRIANGLE_STRIP),
/* harmony export */   "ONE_MINUS_SRC_ALPHA": () => (/* binding */ ONE_MINUS_SRC_ALPHA),
/* harmony export */   "ARRAY_BUFFER": () => (/* binding */ ARRAY_BUFFER),
/* harmony export */   "ELEMENT_ARRAY_BUFFER": () => (/* binding */ ELEMENT_ARRAY_BUFFER),
/* harmony export */   "STREAM_DRAW": () => (/* binding */ STREAM_DRAW),
/* harmony export */   "STATIC_DRAW": () => (/* binding */ STATIC_DRAW),
/* harmony export */   "DYNAMIC_DRAW": () => (/* binding */ DYNAMIC_DRAW),
/* harmony export */   "CULL_FACE": () => (/* binding */ CULL_FACE),
/* harmony export */   "BLEND": () => (/* binding */ BLEND),
/* harmony export */   "STENCIL_TEST": () => (/* binding */ STENCIL_TEST),
/* harmony export */   "DEPTH_TEST": () => (/* binding */ DEPTH_TEST),
/* harmony export */   "SCISSOR_TEST": () => (/* binding */ SCISSOR_TEST),
/* harmony export */   "UNSIGNED_BYTE": () => (/* binding */ UNSIGNED_BYTE),
/* harmony export */   "UNSIGNED_SHORT": () => (/* binding */ UNSIGNED_SHORT),
/* harmony export */   "UNSIGNED_INT": () => (/* binding */ UNSIGNED_INT),
/* harmony export */   "FLOAT": () => (/* binding */ FLOAT),
/* harmony export */   "RGBA": () => (/* binding */ RGBA),
/* harmony export */   "FRAGMENT_SHADER": () => (/* binding */ FRAGMENT_SHADER),
/* harmony export */   "VERTEX_SHADER": () => (/* binding */ VERTEX_SHADER),
/* harmony export */   "LINK_STATUS": () => (/* binding */ LINK_STATUS),
/* harmony export */   "LINEAR": () => (/* binding */ LINEAR),
/* harmony export */   "TEXTURE_MAG_FILTER": () => (/* binding */ TEXTURE_MAG_FILTER),
/* harmony export */   "TEXTURE_MIN_FILTER": () => (/* binding */ TEXTURE_MIN_FILTER),
/* harmony export */   "TEXTURE_WRAP_S": () => (/* binding */ TEXTURE_WRAP_S),
/* harmony export */   "TEXTURE_WRAP_T": () => (/* binding */ TEXTURE_WRAP_T),
/* harmony export */   "TEXTURE_2D": () => (/* binding */ TEXTURE_2D),
/* harmony export */   "TEXTURE0": () => (/* binding */ TEXTURE0),
/* harmony export */   "CLAMP_TO_EDGE": () => (/* binding */ CLAMP_TO_EDGE),
/* harmony export */   "COMPILE_STATUS": () => (/* binding */ COMPILE_STATUS),
/* harmony export */   "FRAMEBUFFER": () => (/* binding */ FRAMEBUFFER),
/* harmony export */   "getContext": () => (/* binding */ getContext),
/* harmony export */   "DEBUG": () => (/* binding */ DEBUG),
/* harmony export */   "HAS": () => (/* binding */ HAS),
/* harmony export */   "MAX_TEXTURE_SIZE": () => (/* binding */ MAX_TEXTURE_SIZE),
/* harmony export */   "EXTENSIONS": () => (/* binding */ EXTENSIONS)
/* harmony export */ });
/**
 * @module ol/webgl
 */


/**
 * Constants taken from goog.webgl
 */


/**
 * @const
 * @type {number}
 */
var ONE = 1;


/**
 * @const
 * @type {number}
 */
var SRC_ALPHA = 0x0302;


/**
 * @const
 * @type {number}
 */
var COLOR_ATTACHMENT0 = 0x8CE0;


/**
 * @const
 * @type {number}
 */
var COLOR_BUFFER_BIT = 0x00004000;


/**
 * @const
 * @type {number}
 */
var TRIANGLES = 0x0004;


/**
 * @const
 * @type {number}
 */
var TRIANGLE_STRIP = 0x0005;


/**
 * @const
 * @type {number}
 */
var ONE_MINUS_SRC_ALPHA = 0x0303;


/**
 * @const
 * @type {number}
 */
var ARRAY_BUFFER = 0x8892;


/**
 * @const
 * @type {number}
 */
var ELEMENT_ARRAY_BUFFER = 0x8893;


/**
 * @const
 * @type {number}
 */
var STREAM_DRAW = 0x88E0;


/**
 * @const
 * @type {number}
 */
var STATIC_DRAW = 0x88E4;


/**
 * @const
 * @type {number}
 */
var DYNAMIC_DRAW = 0x88E8;


/**
 * @const
 * @type {number}
 */
var CULL_FACE = 0x0B44;


/**
 * @const
 * @type {number}
 */
var BLEND = 0x0BE2;


/**
 * @const
 * @type {number}
 */
var STENCIL_TEST = 0x0B90;


/**
 * @const
 * @type {number}
 */
var DEPTH_TEST = 0x0B71;


/**
 * @const
 * @type {number}
 */
var SCISSOR_TEST = 0x0C11;


/**
 * @const
 * @type {number}
 */
var UNSIGNED_BYTE = 0x1401;


/**
 * @const
 * @type {number}
 */
var UNSIGNED_SHORT = 0x1403;


/**
 * @const
 * @type {number}
 */
var UNSIGNED_INT = 0x1405;


/**
 * @const
 * @type {number}
 */
var FLOAT = 0x1406;


/**
 * @const
 * @type {number}
 */
var RGBA = 0x1908;


/**
 * @const
 * @type {number}
 */
var FRAGMENT_SHADER = 0x8B30;


/**
 * @const
 * @type {number}
 */
var VERTEX_SHADER = 0x8B31;


/**
 * @const
 * @type {number}
 */
var LINK_STATUS = 0x8B82;


/**
 * @const
 * @type {number}
 */
var LINEAR = 0x2601;


/**
 * @const
 * @type {number}
 */
var TEXTURE_MAG_FILTER = 0x2800;


/**
 * @const
 * @type {number}
 */
var TEXTURE_MIN_FILTER = 0x2801;


/**
 * @const
 * @type {number}
 */
var TEXTURE_WRAP_S = 0x2802;


/**
 * @const
 * @type {number}
 */
var TEXTURE_WRAP_T = 0x2803;


/**
 * @const
 * @type {number}
 */
var TEXTURE_2D = 0x0DE1;


/**
 * @const
 * @type {number}
 */
var TEXTURE0 = 0x84C0;


/**
 * @const
 * @type {number}
 */
var CLAMP_TO_EDGE = 0x812F;


/**
 * @const
 * @type {number}
 */
var COMPILE_STATUS = 0x8B81;


/**
 * @const
 * @type {number}
 */
var FRAMEBUFFER = 0x8D40;


/** end of goog.webgl constants
 */


/**
 * @const
 * @type {Array<string>}
 */
var CONTEXT_IDS = [
  'experimental-webgl',
  'webgl',
  'webkit-3d',
  'moz-webgl'
];


/**
 * @param {HTMLCanvasElement} canvas Canvas.
 * @param {Object=} opt_attributes Attributes.
 * @return {WebGLRenderingContext} WebGL rendering context.
 */
function getContext(canvas, opt_attributes) {
  var ii = CONTEXT_IDS.length;
  for (var i = 0; i < ii; ++i) {
    try {
      var context = canvas.getContext(CONTEXT_IDS[i], opt_attributes);
      if (context) {
        return /** @type {!WebGLRenderingContext} */ (context);
      }
    } catch (e) {
      // pass
    }
  }
  return null;
}


/**
 * Include debuggable shader sources.  Default is `true`. This should be set to
 * `false` for production builds.
 * @type {boolean}
 */
var DEBUG = true;


/**
 * The maximum supported WebGL texture size in pixels. If WebGL is not
 * supported, the value is set to `undefined`.
 * @type {number|undefined}
 */
var MAX_TEXTURE_SIZE; // value is set below


/**
 * List of supported WebGL extensions.
 * @type {Array<string>}
 */
var EXTENSIONS; // value is set below


/**
 * True if both OpenLayers and browser support WebGL.
 * @type {boolean}
 * @api
 */
var HAS = false;

//TODO Remove side effects
if (typeof window !== 'undefined' && 'WebGLRenderingContext' in window) {
  try {
    var canvas = /** @type {HTMLCanvasElement} */ (document.createElement('canvas'));
    var gl = getContext(canvas, {failIfMajorPerformanceCaveat: true});
    if (gl) {
      HAS = true;
      MAX_TEXTURE_SIZE = /** @type {number} */ (gl.getParameter(gl.MAX_TEXTURE_SIZE));
      EXTENSIONS = gl.getSupportedExtensions();
    }
  } catch (e) {
    // pass
  }
}



//# sourceMappingURL=webgl.js.map

/***/ }),

/***/ "./node_modules/quickselect/quickselect.js":
/*!*************************************************!*\
  !*** ./node_modules/quickselect/quickselect.js ***!
  \*************************************************/
/***/ (function(module) {

(function (global, factory) {
	 true ? module.exports = factory() :
	0;
}(this, (function () { 'use strict';

function quickselect(arr, k, left, right, compare) {
    quickselectStep(arr, k, left || 0, right || (arr.length - 1), compare || defaultCompare);
}

function quickselectStep(arr, k, left, right, compare) {

    while (right > left) {
        if (right - left > 600) {
            var n = right - left + 1;
            var m = k - left + 1;
            var z = Math.log(n);
            var s = 0.5 * Math.exp(2 * z / 3);
            var sd = 0.5 * Math.sqrt(z * s * (n - s) / n) * (m - n / 2 < 0 ? -1 : 1);
            var newLeft = Math.max(left, Math.floor(k - m * s / n + sd));
            var newRight = Math.min(right, Math.floor(k + (n - m) * s / n + sd));
            quickselectStep(arr, k, newLeft, newRight, compare);
        }

        var t = arr[k];
        var i = left;
        var j = right;

        swap(arr, left, k);
        if (compare(arr[right], t) > 0) swap(arr, left, right);

        while (i < j) {
            swap(arr, i, j);
            i++;
            j--;
            while (compare(arr[i], t) < 0) i++;
            while (compare(arr[j], t) > 0) j--;
        }

        if (compare(arr[left], t) === 0) swap(arr, left, j);
        else {
            j++;
            swap(arr, j, right);
        }

        if (j <= k) left = j + 1;
        if (k <= j) right = j - 1;
    }
}

function swap(arr, i, j) {
    var tmp = arr[i];
    arr[i] = arr[j];
    arr[j] = tmp;
}

function defaultCompare(a, b) {
    return a < b ? -1 : a > b ? 1 : 0;
}

return quickselect;

})));


/***/ }),

/***/ "./node_modules/rbush/index.js":
/*!*************************************!*\
  !*** ./node_modules/rbush/index.js ***!
  \*************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


module.exports = rbush;
module.exports["default"] = rbush;

var quickselect = __webpack_require__(/*! quickselect */ "./node_modules/quickselect/quickselect.js");

function rbush(maxEntries, format) {
    if (!(this instanceof rbush)) return new rbush(maxEntries, format);

    // max entries in a node is 9 by default; min node fill is 40% for best performance
    this._maxEntries = Math.max(4, maxEntries || 9);
    this._minEntries = Math.max(2, Math.ceil(this._maxEntries * 0.4));

    if (format) {
        this._initFormat(format);
    }

    this.clear();
}

rbush.prototype = {

    all: function () {
        return this._all(this.data, []);
    },

    search: function (bbox) {

        var node = this.data,
            result = [],
            toBBox = this.toBBox;

        if (!intersects(bbox, node)) return result;

        var nodesToSearch = [],
            i, len, child, childBBox;

        while (node) {
            for (i = 0, len = node.children.length; i < len; i++) {

                child = node.children[i];
                childBBox = node.leaf ? toBBox(child) : child;

                if (intersects(bbox, childBBox)) {
                    if (node.leaf) result.push(child);
                    else if (contains(bbox, childBBox)) this._all(child, result);
                    else nodesToSearch.push(child);
                }
            }
            node = nodesToSearch.pop();
        }

        return result;
    },

    collides: function (bbox) {

        var node = this.data,
            toBBox = this.toBBox;

        if (!intersects(bbox, node)) return false;

        var nodesToSearch = [],
            i, len, child, childBBox;

        while (node) {
            for (i = 0, len = node.children.length; i < len; i++) {

                child = node.children[i];
                childBBox = node.leaf ? toBBox(child) : child;

                if (intersects(bbox, childBBox)) {
                    if (node.leaf || contains(bbox, childBBox)) return true;
                    nodesToSearch.push(child);
                }
            }
            node = nodesToSearch.pop();
        }

        return false;
    },

    load: function (data) {
        if (!(data && data.length)) return this;

        if (data.length < this._minEntries) {
            for (var i = 0, len = data.length; i < len; i++) {
                this.insert(data[i]);
            }
            return this;
        }

        // recursively build the tree with the given data from scratch using OMT algorithm
        var node = this._build(data.slice(), 0, data.length - 1, 0);

        if (!this.data.children.length) {
            // save as is if tree is empty
            this.data = node;

        } else if (this.data.height === node.height) {
            // split root if trees have the same height
            this._splitRoot(this.data, node);

        } else {
            if (this.data.height < node.height) {
                // swap trees if inserted one is bigger
                var tmpNode = this.data;
                this.data = node;
                node = tmpNode;
            }

            // insert the small tree into the large tree at appropriate level
            this._insert(node, this.data.height - node.height - 1, true);
        }

        return this;
    },

    insert: function (item) {
        if (item) this._insert(item, this.data.height - 1);
        return this;
    },

    clear: function () {
        this.data = createNode([]);
        return this;
    },

    remove: function (item, equalsFn) {
        if (!item) return this;

        var node = this.data,
            bbox = this.toBBox(item),
            path = [],
            indexes = [],
            i, parent, index, goingUp;

        // depth-first iterative tree traversal
        while (node || path.length) {

            if (!node) { // go up
                node = path.pop();
                parent = path[path.length - 1];
                i = indexes.pop();
                goingUp = true;
            }

            if (node.leaf) { // check current node
                index = findItem(item, node.children, equalsFn);

                if (index !== -1) {
                    // item found, remove the item and condense tree upwards
                    node.children.splice(index, 1);
                    path.push(node);
                    this._condense(path);
                    return this;
                }
            }

            if (!goingUp && !node.leaf && contains(node, bbox)) { // go down
                path.push(node);
                indexes.push(i);
                i = 0;
                parent = node;
                node = node.children[0];

            } else if (parent) { // go right
                i++;
                node = parent.children[i];
                goingUp = false;

            } else node = null; // nothing found
        }

        return this;
    },

    toBBox: function (item) { return item; },

    compareMinX: compareNodeMinX,
    compareMinY: compareNodeMinY,

    toJSON: function () { return this.data; },

    fromJSON: function (data) {
        this.data = data;
        return this;
    },

    _all: function (node, result) {
        var nodesToSearch = [];
        while (node) {
            if (node.leaf) result.push.apply(result, node.children);
            else nodesToSearch.push.apply(nodesToSearch, node.children);

            node = nodesToSearch.pop();
        }
        return result;
    },

    _build: function (items, left, right, height) {

        var N = right - left + 1,
            M = this._maxEntries,
            node;

        if (N <= M) {
            // reached leaf level; return leaf
            node = createNode(items.slice(left, right + 1));
            calcBBox(node, this.toBBox);
            return node;
        }

        if (!height) {
            // target height of the bulk-loaded tree
            height = Math.ceil(Math.log(N) / Math.log(M));

            // target number of root entries to maximize storage utilization
            M = Math.ceil(N / Math.pow(M, height - 1));
        }

        node = createNode([]);
        node.leaf = false;
        node.height = height;

        // split the items into M mostly square tiles

        var N2 = Math.ceil(N / M),
            N1 = N2 * Math.ceil(Math.sqrt(M)),
            i, j, right2, right3;

        multiSelect(items, left, right, N1, this.compareMinX);

        for (i = left; i <= right; i += N1) {

            right2 = Math.min(i + N1 - 1, right);

            multiSelect(items, i, right2, N2, this.compareMinY);

            for (j = i; j <= right2; j += N2) {

                right3 = Math.min(j + N2 - 1, right2);

                // pack each entry recursively
                node.children.push(this._build(items, j, right3, height - 1));
            }
        }

        calcBBox(node, this.toBBox);

        return node;
    },

    _chooseSubtree: function (bbox, node, level, path) {

        var i, len, child, targetNode, area, enlargement, minArea, minEnlargement;

        while (true) {
            path.push(node);

            if (node.leaf || path.length - 1 === level) break;

            minArea = minEnlargement = Infinity;

            for (i = 0, len = node.children.length; i < len; i++) {
                child = node.children[i];
                area = bboxArea(child);
                enlargement = enlargedArea(bbox, child) - area;

                // choose entry with the least area enlargement
                if (enlargement < minEnlargement) {
                    minEnlargement = enlargement;
                    minArea = area < minArea ? area : minArea;
                    targetNode = child;

                } else if (enlargement === minEnlargement) {
                    // otherwise choose one with the smallest area
                    if (area < minArea) {
                        minArea = area;
                        targetNode = child;
                    }
                }
            }

            node = targetNode || node.children[0];
        }

        return node;
    },

    _insert: function (item, level, isNode) {

        var toBBox = this.toBBox,
            bbox = isNode ? item : toBBox(item),
            insertPath = [];

        // find the best node for accommodating the item, saving all nodes along the path too
        var node = this._chooseSubtree(bbox, this.data, level, insertPath);

        // put the item into the node
        node.children.push(item);
        extend(node, bbox);

        // split on node overflow; propagate upwards if necessary
        while (level >= 0) {
            if (insertPath[level].children.length > this._maxEntries) {
                this._split(insertPath, level);
                level--;
            } else break;
        }

        // adjust bboxes along the insertion path
        this._adjustParentBBoxes(bbox, insertPath, level);
    },

    // split overflowed node into two
    _split: function (insertPath, level) {

        var node = insertPath[level],
            M = node.children.length,
            m = this._minEntries;

        this._chooseSplitAxis(node, m, M);

        var splitIndex = this._chooseSplitIndex(node, m, M);

        var newNode = createNode(node.children.splice(splitIndex, node.children.length - splitIndex));
        newNode.height = node.height;
        newNode.leaf = node.leaf;

        calcBBox(node, this.toBBox);
        calcBBox(newNode, this.toBBox);

        if (level) insertPath[level - 1].children.push(newNode);
        else this._splitRoot(node, newNode);
    },

    _splitRoot: function (node, newNode) {
        // split root node
        this.data = createNode([node, newNode]);
        this.data.height = node.height + 1;
        this.data.leaf = false;
        calcBBox(this.data, this.toBBox);
    },

    _chooseSplitIndex: function (node, m, M) {

        var i, bbox1, bbox2, overlap, area, minOverlap, minArea, index;

        minOverlap = minArea = Infinity;

        for (i = m; i <= M - m; i++) {
            bbox1 = distBBox(node, 0, i, this.toBBox);
            bbox2 = distBBox(node, i, M, this.toBBox);

            overlap = intersectionArea(bbox1, bbox2);
            area = bboxArea(bbox1) + bboxArea(bbox2);

            // choose distribution with minimum overlap
            if (overlap < minOverlap) {
                minOverlap = overlap;
                index = i;

                minArea = area < minArea ? area : minArea;

            } else if (overlap === minOverlap) {
                // otherwise choose distribution with minimum area
                if (area < minArea) {
                    minArea = area;
                    index = i;
                }
            }
        }

        return index;
    },

    // sorts node children by the best axis for split
    _chooseSplitAxis: function (node, m, M) {

        var compareMinX = node.leaf ? this.compareMinX : compareNodeMinX,
            compareMinY = node.leaf ? this.compareMinY : compareNodeMinY,
            xMargin = this._allDistMargin(node, m, M, compareMinX),
            yMargin = this._allDistMargin(node, m, M, compareMinY);

        // if total distributions margin value is minimal for x, sort by minX,
        // otherwise it's already sorted by minY
        if (xMargin < yMargin) node.children.sort(compareMinX);
    },

    // total margin of all possible split distributions where each node is at least m full
    _allDistMargin: function (node, m, M, compare) {

        node.children.sort(compare);

        var toBBox = this.toBBox,
            leftBBox = distBBox(node, 0, m, toBBox),
            rightBBox = distBBox(node, M - m, M, toBBox),
            margin = bboxMargin(leftBBox) + bboxMargin(rightBBox),
            i, child;

        for (i = m; i < M - m; i++) {
            child = node.children[i];
            extend(leftBBox, node.leaf ? toBBox(child) : child);
            margin += bboxMargin(leftBBox);
        }

        for (i = M - m - 1; i >= m; i--) {
            child = node.children[i];
            extend(rightBBox, node.leaf ? toBBox(child) : child);
            margin += bboxMargin(rightBBox);
        }

        return margin;
    },

    _adjustParentBBoxes: function (bbox, path, level) {
        // adjust bboxes along the given tree path
        for (var i = level; i >= 0; i--) {
            extend(path[i], bbox);
        }
    },

    _condense: function (path) {
        // go through the path, removing empty nodes and updating bboxes
        for (var i = path.length - 1, siblings; i >= 0; i--) {
            if (path[i].children.length === 0) {
                if (i > 0) {
                    siblings = path[i - 1].children;
                    siblings.splice(siblings.indexOf(path[i]), 1);

                } else this.clear();

            } else calcBBox(path[i], this.toBBox);
        }
    },

    _initFormat: function (format) {
        // data format (minX, minY, maxX, maxY accessors)

        // uses eval-type function compilation instead of just accepting a toBBox function
        // because the algorithms are very sensitive to sorting functions performance,
        // so they should be dead simple and without inner calls

        var compareArr = ['return a', ' - b', ';'];

        this.compareMinX = new Function('a', 'b', compareArr.join(format[0]));
        this.compareMinY = new Function('a', 'b', compareArr.join(format[1]));

        this.toBBox = new Function('a',
            'return {minX: a' + format[0] +
            ', minY: a' + format[1] +
            ', maxX: a' + format[2] +
            ', maxY: a' + format[3] + '};');
    }
};

function findItem(item, items, equalsFn) {
    if (!equalsFn) return items.indexOf(item);

    for (var i = 0; i < items.length; i++) {
        if (equalsFn(item, items[i])) return i;
    }
    return -1;
}

// calculate node's bbox from bboxes of its children
function calcBBox(node, toBBox) {
    distBBox(node, 0, node.children.length, toBBox, node);
}

// min bounding rectangle of node children from k to p-1
function distBBox(node, k, p, toBBox, destNode) {
    if (!destNode) destNode = createNode(null);
    destNode.minX = Infinity;
    destNode.minY = Infinity;
    destNode.maxX = -Infinity;
    destNode.maxY = -Infinity;

    for (var i = k, child; i < p; i++) {
        child = node.children[i];
        extend(destNode, node.leaf ? toBBox(child) : child);
    }

    return destNode;
}

function extend(a, b) {
    a.minX = Math.min(a.minX, b.minX);
    a.minY = Math.min(a.minY, b.minY);
    a.maxX = Math.max(a.maxX, b.maxX);
    a.maxY = Math.max(a.maxY, b.maxY);
    return a;
}

function compareNodeMinX(a, b) { return a.minX - b.minX; }
function compareNodeMinY(a, b) { return a.minY - b.minY; }

function bboxArea(a)   { return (a.maxX - a.minX) * (a.maxY - a.minY); }
function bboxMargin(a) { return (a.maxX - a.minX) + (a.maxY - a.minY); }

function enlargedArea(a, b) {
    return (Math.max(b.maxX, a.maxX) - Math.min(b.minX, a.minX)) *
           (Math.max(b.maxY, a.maxY) - Math.min(b.minY, a.minY));
}

function intersectionArea(a, b) {
    var minX = Math.max(a.minX, b.minX),
        minY = Math.max(a.minY, b.minY),
        maxX = Math.min(a.maxX, b.maxX),
        maxY = Math.min(a.maxY, b.maxY);

    return Math.max(0, maxX - minX) *
           Math.max(0, maxY - minY);
}

function contains(a, b) {
    return a.minX <= b.minX &&
           a.minY <= b.minY &&
           b.maxX <= a.maxX &&
           b.maxY <= a.maxY;
}

function intersects(a, b) {
    return b.minX <= a.maxX &&
           b.minY <= a.maxY &&
           b.maxX >= a.minX &&
           b.maxY >= a.minY;
}

function createNode(children) {
    return {
        children: children,
        height: 1,
        leaf: true,
        minX: Infinity,
        minY: Infinity,
        maxX: -Infinity,
        maxY: -Infinity
    };
}

// sort an array so that items come in groups of n unsorted items, with groups sorted between each other;
// combines selection algorithm with binary divide & conquer approach

function multiSelect(arr, left, right, n, compare) {
    var stack = [left, right],
        mid;

    while (stack.length) {
        right = stack.pop();
        left = stack.pop();

        if (right - left <= n) continue;

        mid = left + Math.ceil((right - left) / n / 2) * n;
        quickselect(arr, mid, left, right, compare);

        stack.push(left, mid, mid, right);
    }
}


/***/ }),

/***/ "./src/resources/assets/js/components/candidatesImageGrid.vue":
/*!********************************************************************!*\
  !*** ./src/resources/assets/js/components/candidatesImageGrid.vue ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _candidatesImageGrid_vue_vue_type_template_id_7b766995___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./candidatesImageGrid.vue?vue&type=template&id=7b766995& */ "./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=template&id=7b766995&");
/* harmony import */ var _candidatesImageGrid_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./candidatesImageGrid.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _candidatesImageGrid_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _candidatesImageGrid_vue_vue_type_template_id_7b766995___WEBPACK_IMPORTED_MODULE_0__.render,
  _candidatesImageGrid_vue_vue_type_template_id_7b766995___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/candidatesImageGrid.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/candidatesImageGridImage.vue":
/*!*************************************************************************!*\
  !*** ./src/resources/assets/js/components/candidatesImageGridImage.vue ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _candidatesImageGridImage_vue_vue_type_template_id_4cc57576___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./candidatesImageGridImage.vue?vue&type=template&id=4cc57576& */ "./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=template&id=4cc57576&");
/* harmony import */ var _candidatesImageGridImage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./candidatesImageGridImage.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _candidatesImageGridImage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _candidatesImageGridImage_vue_vue_type_template_id_4cc57576___WEBPACK_IMPORTED_MODULE_0__.render,
  _candidatesImageGridImage_vue_vue_type_template_id_4cc57576___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/candidatesImageGridImage.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/proposalsImageGrid.vue":
/*!*******************************************************************!*\
  !*** ./src/resources/assets/js/components/proposalsImageGrid.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _proposalsImageGrid_vue_vue_type_template_id_5c389fec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./proposalsImageGrid.vue?vue&type=template&id=5c389fec& */ "./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=template&id=5c389fec&");
/* harmony import */ var _proposalsImageGrid_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./proposalsImageGrid.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _proposalsImageGrid_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _proposalsImageGrid_vue_vue_type_template_id_5c389fec___WEBPACK_IMPORTED_MODULE_0__.render,
  _proposalsImageGrid_vue_vue_type_template_id_5c389fec___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/proposalsImageGrid.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/proposalsImageGridImage.vue":
/*!************************************************************************!*\
  !*** ./src/resources/assets/js/components/proposalsImageGridImage.vue ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _proposalsImageGridImage_vue_vue_type_template_id_4f6a9d7f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./proposalsImageGridImage.vue?vue&type=template&id=4f6a9d7f& */ "./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=template&id=4f6a9d7f&");
/* harmony import */ var _proposalsImageGridImage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./proposalsImageGridImage.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _proposalsImageGridImage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _proposalsImageGridImage_vue_vue_type_template_id_4f6a9d7f___WEBPACK_IMPORTED_MODULE_0__.render,
  _proposalsImageGridImage_vue_vue_type_template_id_4f6a9d7f___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/proposalsImageGridImage.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/refineCandidatesCanvas.vue":
/*!***********************************************************************!*\
  !*** ./src/resources/assets/js/components/refineCandidatesCanvas.vue ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _refineCandidatesCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./refineCandidatesCanvas.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/refineCandidatesCanvas.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _refineCandidatesCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/refineCandidatesCanvas.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/refineCandidatesTab.vue":
/*!********************************************************************!*\
  !*** ./src/resources/assets/js/components/refineCandidatesTab.vue ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _refineCandidatesTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./refineCandidatesTab.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/refineCandidatesTab.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _refineCandidatesTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/refineCandidatesTab.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/refineCanvas.vue":
/*!*************************************************************!*\
  !*** ./src/resources/assets/js/components/refineCanvas.vue ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _refineCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./refineCanvas.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/refineCanvas.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _refineCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/refineCanvas.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/refineProposalsTab.vue":
/*!*******************************************************************!*\
  !*** ./src/resources/assets/js/components/refineProposalsTab.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _refineProposalsTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./refineProposalsTab.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/refineProposalsTab.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _refineProposalsTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/refineProposalsTab.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/selectCandidatesTab.vue":
/*!********************************************************************!*\
  !*** ./src/resources/assets/js/components/selectCandidatesTab.vue ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _selectCandidatesTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./selectCandidatesTab.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/selectCandidatesTab.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _selectCandidatesTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/selectCandidatesTab.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/selectProposalsTab.vue":
/*!*******************************************************************!*\
  !*** ./src/resources/assets/js/components/selectProposalsTab.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _selectProposalsTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./selectProposalsTab.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/components/selectProposalsTab.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _selectProposalsTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/components/selectProposalsTab.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/form.vue":
/*!******************************************!*\
  !*** ./src/resources/assets/js/form.vue ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./form.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/form.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/form.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/show.vue":
/*!******************************************!*\
  !*** ./src/resources/assets/js/show.vue ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./show.vue?vue&type=script&lang=js& */ "./src/resources/assets/js/show.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "src/resources/assets/js/show.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGrid_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./candidatesImageGrid.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGrid_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************!*\
  !*** ./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGridImage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./candidatesImageGridImage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGridImage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGrid_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./proposalsImageGrid.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGrid_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGridImage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./proposalsImageGridImage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGridImage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/refineCandidatesCanvas.vue?vue&type=script&lang=js&":
/*!************************************************************************************************!*\
  !*** ./src/resources/assets/js/components/refineCandidatesCanvas.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_refineCandidatesCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./refineCandidatesCanvas.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCandidatesCanvas.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_refineCandidatesCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/refineCandidatesTab.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./src/resources/assets/js/components/refineCandidatesTab.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_refineCandidatesTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./refineCandidatesTab.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCandidatesTab.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_refineCandidatesTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/refineCanvas.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./src/resources/assets/js/components/refineCanvas.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_refineCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./refineCanvas.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineCanvas.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_refineCanvas_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/refineProposalsTab.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./src/resources/assets/js/components/refineProposalsTab.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_refineProposalsTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./refineProposalsTab.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/refineProposalsTab.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_refineProposalsTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/selectCandidatesTab.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./src/resources/assets/js/components/selectCandidatesTab.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_selectCandidatesTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./selectCandidatesTab.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/selectCandidatesTab.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_selectCandidatesTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/selectProposalsTab.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./src/resources/assets/js/components/selectProposalsTab.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_selectProposalsTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./selectProposalsTab.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/selectProposalsTab.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_selectProposalsTab_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/form.vue?vue&type=script&lang=js&":
/*!*******************************************************************!*\
  !*** ./src/resources/assets/js/form.vue?vue&type=script&lang=js& ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./form.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/form.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/show.vue?vue&type=script&lang=js&":
/*!*******************************************************************!*\
  !*** ./src/resources/assets/js/show.vue?vue&type=script&lang=js& ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./show.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/show.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=template&id=7b766995&":
/*!***************************************************************************************************!*\
  !*** ./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=template&id=7b766995& ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGrid_vue_vue_type_template_id_7b766995___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGrid_vue_vue_type_template_id_7b766995___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGrid_vue_vue_type_template_id_7b766995___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./candidatesImageGrid.vue?vue&type=template&id=7b766995& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=template&id=7b766995&");


/***/ }),

/***/ "./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=template&id=4cc57576&":
/*!********************************************************************************************************!*\
  !*** ./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=template&id=4cc57576& ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGridImage_vue_vue_type_template_id_4cc57576___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGridImage_vue_vue_type_template_id_4cc57576___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_candidatesImageGridImage_vue_vue_type_template_id_4cc57576___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./candidatesImageGridImage.vue?vue&type=template&id=4cc57576& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=template&id=4cc57576&");


/***/ }),

/***/ "./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=template&id=5c389fec&":
/*!**************************************************************************************************!*\
  !*** ./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=template&id=5c389fec& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGrid_vue_vue_type_template_id_5c389fec___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGrid_vue_vue_type_template_id_5c389fec___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGrid_vue_vue_type_template_id_5c389fec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./proposalsImageGrid.vue?vue&type=template&id=5c389fec& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=template&id=5c389fec&");


/***/ }),

/***/ "./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=template&id=4f6a9d7f&":
/*!*******************************************************************************************************!*\
  !*** ./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=template&id=4f6a9d7f& ***!
  \*******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGridImage_vue_vue_type_template_id_4f6a9d7f___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGridImage_vue_vue_type_template_id_4f6a9d7f___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_proposalsImageGridImage_vue_vue_type_template_id_4f6a9d7f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./proposalsImageGridImage.vue?vue&type=template&id=4f6a9d7f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=template&id=4f6a9d7f&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=template&id=7b766995&":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGrid.vue?vue&type=template&id=7b766995& ***!
  \******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      staticClass: "image-grid",
      on: {
        wheel: function ($event) {
          $event.preventDefault()
          return _vm.scroll.apply(null, arguments)
        },
      },
    },
    [
      _c(
        "div",
        { ref: "images", staticClass: "image-grid__images" },
        _vm._l(_vm.displayedImages, function (image) {
          return _c("image-grid-image", {
            key: image.id,
            attrs: {
              image: image,
              "empty-url": _vm.emptyUrl,
              selectable: !_vm.isConverted(image),
              "selected-icon": _vm.selectedIcon,
            },
            on: { select: _vm.emitSelect },
          })
        }),
        1
      ),
      _vm._v(" "),
      _vm.canScroll
        ? _c("image-grid-progress", {
            attrs: { progress: _vm.progress },
            on: {
              top: _vm.jumpToStart,
              "prev-page": _vm.reversePage,
              "prev-row": _vm.reverseRow,
              jump: _vm.jumpToPercent,
              "next-row": _vm.advanceRow,
              "next-page": _vm.advancePage,
              bottom: _vm.jumpToEnd,
            },
          })
        : _vm._e(),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=template&id=4cc57576&":
/*!***********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/candidatesImageGridImage.vue?vue&type=template&id=4cc57576& ***!
  \***********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "figure",
    {
      staticClass: "image-grid__image image-grid__image--annotation-candidate",
      class: _vm.classObject,
      attrs: { title: _vm.title },
    },
    [
      _vm.showIcon
        ? _c("div", { staticClass: "image-icon" }, [
            _c("i", { staticClass: "fas", class: _vm.iconClass }),
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("img", {
        attrs: { src: _vm.srcUrl },
        on: { click: _vm.toggleSelect, error: _vm.showEmptyImage },
      }),
      _vm._v(" "),
      _vm.selected
        ? _c("div", { staticClass: "attached-label" }, [
            _c("span", {
              staticClass: "attached-label__color",
              style: _vm.labelStyle,
            }),
            _vm._v(" "),
            _c("span"),
            _vm._v(" "),
            _c("span", {
              staticClass: "attached-label__name",
              domProps: { textContent: _vm._s(_vm.label.name) },
            }),
          ])
        : _vm._e(),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=template&id=5c389fec&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGrid.vue?vue&type=template&id=5c389fec& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      staticClass: "image-grid",
      on: {
        wheel: function ($event) {
          $event.preventDefault()
          return _vm.scroll.apply(null, arguments)
        },
      },
    },
    [
      _c(
        "div",
        { ref: "images", staticClass: "image-grid__images" },
        _vm._l(_vm.displayedImages, function (image) {
          return _c("image-grid-image", {
            key: image.id,
            attrs: {
              image: image,
              "empty-url": _vm.emptyUrl,
              selectable: _vm.selectable,
              "selected-fade": _vm.selectable,
              "small-icon": !_vm.selectable,
              "selected-icon": _vm.selectedIcon,
            },
            on: { select: _vm.emitSelect },
          })
        }),
        1
      ),
      _vm._v(" "),
      _vm.canScroll
        ? _c("image-grid-progress", {
            attrs: { progress: _vm.progress },
            on: {
              top: _vm.jumpToStart,
              "prev-page": _vm.reversePage,
              "prev-row": _vm.reverseRow,
              jump: _vm.jumpToPercent,
              "next-row": _vm.advanceRow,
              "next-page": _vm.advancePage,
              bottom: _vm.jumpToEnd,
            },
          })
        : _vm._e(),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=template&id=4f6a9d7f&":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./src/resources/assets/js/components/proposalsImageGridImage.vue?vue&type=template&id=4f6a9d7f& ***!
  \**********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "figure",
    {
      staticClass: "image-grid__image image-grid__image--annotation-candidate",
      class: _vm.classObject,
      attrs: { title: _vm.title },
    },
    [
      _vm.showIcon
        ? _c("div", { staticClass: "image-icon" }, [
            _c("i", { staticClass: "fas", class: _vm.iconClass }),
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("img", {
        attrs: { src: _vm.srcUrl },
        on: { click: _vm.toggleSelect, error: _vm.showEmptyImage },
      }),
      _vm._v(" "),
      _vm.labelExists
        ? _c("div", { staticClass: "attached-label" }, [
            _c("span", {
              staticClass: "attached-label__color",
              style: _vm.labelStyle,
            }),
            _vm._v(" "),
            _c("span", {
              staticClass: "attached-label__name",
              domProps: { textContent: _vm._s(_vm.label.name) },
            }),
          ])
        : _vm._e(),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/scripts/main": 0,
/******/ 			"assets/styles/main": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkbiigle_maia"] = self["webpackChunkbiigle_maia"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/styles/main"], () => (__webpack_require__("./src/resources/assets/js/main.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/styles/main"], () => (__webpack_require__("./src/resources/assets/sass/main.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
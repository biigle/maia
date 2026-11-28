import {AnnotationGuideline} from '../import.js';
import {AnnotationGuidelineSelector} from '../import.js';
import {LabelIndicator} from '../import.js';

/**
 * Annotation guideline selection and label indicator for the label trees of the
 * annotation candidate tabs.
 *
 * The chosen guideline is stored in the parent so both tabs share it.
 */
export default {
    emits: ['select-guideline'],
    components: {
        annotationGuidelineSelector: AnnotationGuidelineSelector,
        labelIndicator: LabelIndicator,
    },
    props: {
        selectedLabel: {
            type: Object,
            default: null,
        },
        annotationGuideline: {
            type: AnnotationGuideline,
            default: null,
        },
        annotationGuidelines: {
            type: Array,
            default: () => [],
        },
        mustUseAnnotationGuideline: {
            type: Boolean,
            default: false,
        },
        volumeId: {
            type: Number,
            required: true,
        },
    },
    computed: {
        labelsInGuideline() {
            return this.annotationGuideline?.labelIds ?? [];
        },
        enforceGuideline() {
            return !!this.annotationGuideline?.isEnforced;
        },
        // The selected label is already highlighted in the label trees, so the
        // indicator is only useful if the guideline has more information.
        showLabelIndicator() {
            if (!this.selectedLabel) {
                return false;
            }

            const id = this.selectedLabel.id;
            const label = this.annotationGuideline?.getLabel(id);

            return !!(this.annotationGuideline?.getLabelShape(id)
                || label?.description
                || label?.reference_image_url);
        },
    },
    methods: {
        handleSelectedGuideline(guideline) {
            this.$emit('select-guideline', guideline);
        },
    },
};

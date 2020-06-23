import Collection from 'ol/Collection';
import OlObject from 'ol/Object';
import RefineCanvas from './refineCanvas';
import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
import {StylesStore} from '../import';

/**
 * A letiant of the annotation canvas used for the refinement of annotation candidates.
 *
 * @type {Object}
 */
export default {
    mixins: [RefineCanvas],
    props: {
        convertedAnnotations: {
            type: Array,
            default() {
                return [];
            },
        },
    },
    methods: {
        createConvertedAnnotationsLayer() {
            this.convertedAnnotationFeatures = new Collection();
            this.convertedAnnotationSource = new VectorSource({
                features: this.convertedAnnotationFeatures
            });

            let fakeFeature = new OlObject();
            fakeFeature.set('color', '999999');

            this.convertedAnnotationLayer = new VectorLayer({
                source: this.convertedAnnotationSource,
                // Should be below unselected candidates which are at index 99. Else
                // the attach label interaction doesn't work.
                zIndex: 98,
                updateWhileAnimating: true,
                updateWhileInteracting: true,
                style: StylesStore.features(fakeFeature),
            });
        },
    },
    watch: {
        convertedAnnotations(annotations) {
            this.refreshAnnotationSource(annotations, this.convertedAnnotationSource);
        },
    },
    created() {
        this.createConvertedAnnotationsLayer();
        this.map.addLayer(this.convertedAnnotationLayer);
    },
};

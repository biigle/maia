<script>
import Collection from '@biigle/ol/Collection';
import OlObject from '@biigle/ol/Object';
import RefineCanvas from './refineCanvas';
import Style from '@biigle/ol/style/Style';
import VectorLayer from '@biigle/ol/layer/Vector';
import VectorSource from '@biigle/ol/source/Vector';
import {StylesStore} from '../import';

/**
 * A variant of the annotation canvas used for the refinement of annotation candidates.
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
            // The style can't be used directly because biigle/maia imports their own
            // @biigle/ol package and the Style object of biigle/core's @biigle/ol does
            // not match this one.
            const featureStyle = StylesStore.features(fakeFeature);

            this.convertedAnnotationLayer = new VectorLayer({
                source: this.convertedAnnotationSource,
                // Should be below unselected candidates which are at index 99. Else
                // the attach label interaction doesn't work.
                zIndex: 98,
                updateWhileAnimating: true,
                updateWhileInteracting: true,
                style: [
                    new Style({
                        stroke: featureStyle[0].getStroke(),
                        image: featureStyle[0].getImage(),
                        fill: featureStyle[0].getFill(),
                    }),
                    new Style({
                        stroke: featureStyle[1].getStroke(),
                    }),
                ],
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
</script>

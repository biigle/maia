biigle.$viewModel("maia-job-form",function(e){new Vue({el:e,data:{showAdvanced:!1},methods:{toggle:function(){this.showAdvanced=!this.showAdvanced}}})}),biigle.$viewModel("maia-show-container",function(e){var t=biigle.$require("maia.job"),s=biigle.$require("maia.states"),n=biigle.$require("maia.api.maiaJob"),a=biigle.$require("maia.api.maiaAnnotation"),i=biigle.$require("messages.store"),o=biigle.$require("annotations.stores.images"),r=[],d={},l=[],c={};new Vue({el:e,mixins:[biigle.$require("core.mixins.loader")],components:{sidebar:biigle.$require("annotations.components.sidebar"),sidebarTab:biigle.$require("core.components.sidebarTab"),selectProposalsTab:biigle.$require("maia.components.selectProposalsTab"),proposalsImageGrid:biigle.$require("maia.components.proposalsImageGrid"),refineProposalsTab:biigle.$require("maia.components.refineProposalsTab"),refineProposalsCanvas:biigle.$require("maia.components.refineProposalsCanvas"),selectCandidatesTab:biigle.$require("maia.components.selectCandidatesTab"),candidatesImageGrid:biigle.$require("maia.components.candidatesImageGrid"),refineCandidatesTab:biigle.$require("maia.components.refineCandidatesTab")},data:{visitedSelectProposalsTab:!1,visitedRefineProposalsTab:!1,visitedSelectCandidatesTab:!1,visitedRefineCandidatesTab:!1,openTab:"info",fetchProposalsPromise:null,hasProposals:!1,selectedProposalIds:{},seenProposalIds:{},lastSelectedProposal:null,currentProposalImage:null,currentProposalImageIndex:null,currentProposals:[],currentProposalsById:{},focussedProposal:null,proposalAnnotationCache:{},fetchCandidatesPromise:null,hasCandidates:!1,selectedCandidateIds:{},currentCandidateImage:null,currentCandidateImageIndex:null,currentCandidates:[],currentCandidatesById:{},candidateAnnotationCache:{}},computed:{infoTabOpen:function(){return"info"===this.openTab},selectProposalsTabOpen:function(){return"select-proposals"===this.openTab},refineProposalsTabOpen:function(){return"refine-proposals"===this.openTab},selectCandidatesTabOpen:function(){return"select-candidates"===this.openTab},refineCandidatesTabOpen:function(){return"refine-candidates"===this.openTab},isInTrainingProposalState:function(){return t.state_id===s["training-proposals"]},isInAnnotationCandidateState:function(){return t.state_id===s["annotation-candidates"]},proposals:function(){return this.hasProposals?r:[]},selectedProposals:function(){return Object.keys(this.selectedProposalIds).map(function(e){return d[e]})},hasSelectedProposals:function(){return this.selectedProposals.length>0},proposalImageIds:function(){var e={};return this.proposals.forEach(function(t){e[t.image_id]=void 0}),Object.keys(e).map(function(e){return parseInt(e,10)})},currentProposalImageId:function(){return this.proposalImageIds[this.currentProposalImageIndex]},nextProposalImageIndex:function(){return(this.currentProposalImageIndex+1)%this.proposalImageIds.length},nextProposalImageId:function(){return this.proposalImageIds[this.nextProposalImageIndex]},nextFocussedProposalImageId:function(){return this.nextFocussedProposal?this.nextFocussedProposal.image_id:this.nextProposalImageId},previousProposalImageIndex:function(){return(this.currentProposalImageIndex-1+this.proposalImageIds.length)%this.proposalImageIds.length},previousProposalImageId:function(){return this.proposalImageIds[this.previousProposalImageIndex]},hasCurrentProposalImage:function(){return null!==this.currentProposalImage},currentSelectedProposals:function(){return this.currentProposals.filter(function(e){return this.selectedProposalIds.hasOwnProperty(e.id)},this)},currentUnselectedProposals:function(){return this.currentProposals.filter(function(e){return!this.selectedProposalIds.hasOwnProperty(e.id)},this)},previousFocussedProposal:function(){var e=(this.selectedProposals.indexOf(this.focussedProposal)-1+this.selectedProposals.length)%this.selectedProposals.length;return this.selectedProposals[e]},nextFocussedProposal:function(){var e=(this.selectedProposals.indexOf(this.focussedProposal)+1)%this.selectedProposals.length;return this.selectedProposals[e]},focussedProposalToShow:function(){return this.refineProposalsTabOpen?this.focussedProposal:null},focussedProposalArray:function(){return this.focussedProposalToShow?this.currentSelectedProposals.filter(function(e){return e.id===this.focussedProposalToShow.id},this):[]},selectedAndSeenProposals:function(){return this.selectedProposals.filter(function(e){return this.seenProposalIds.hasOwnProperty(e.id)},this)},candidates:function(){return this.hasCandidates?l:[]},selectedCandidates:function(){return Object.keys(this.selectedCandidateIds).map(function(e){return c[e]})},hasSelectedCandidates:function(){return this.selectedCandidates.length>0},candidateImageIds:function(){var e={};return this.candidates.forEach(function(t){e[t.image_id]=void 0}),Object.keys(e).map(function(e){return parseInt(e,10)})},currentCandidateImageId:function(){return this.candidateImageIds[this.currentCandidateImageIndex]},nextCandidateImageIndex:function(){return(this.currentCandidateImageIndex+1)%this.candidateImageIds.length},nextCandidateImageId:function(){return this.candidateImageIds[this.nextCandidateImageIndex]},previousCandidateImageIndex:function(){return(this.currentCandidateImageIndex-1+this.candidateImageIds.length)%this.candidateImageIds.length},previousCandidateImageId:function(){return this.candidateImageIds[this.previousCandidateImageIndex]},hasCurrentCandidateImage:function(){return null!==this.currentCandidateImage},currentSelectedCandidates:function(){return this.currentCandidates.filter(function(e){return this.selectedCandidateIds.hasOwnProperty(e.id)},this)},currentUnselectedCandidates:function(){return this.currentCandidates.filter(function(e){return!this.selectedCandidateIds.hasOwnProperty(e.id)},this)}},methods:{handleSidebarToggle:function(){this.$nextTick(function(){this.$refs.proposalsImageGrid.$emit("resize"),this.$refs.candidatesImageGrid.$emit("resize")})},handleTabOpened:function(e){this.openTab=e},setProposals:function(e){r=e.body,r.forEach(function(e){d[e.id]=e,this.setSelectedProposalId(e)},this),this.hasProposals=r.length>0},fetchProposals:function(){return this.fetchProposalsPromise||(this.startLoading(),this.fetchProposalsPromise=n.getTrainingProposals({id:t.id}),this.fetchProposalsPromise.then(this.setProposals).catch(i.handleErrorResponse).finally(this.finishLoading)),this.fetchProposalsPromise},openRefineProposalsTab:function(){this.openTab="refine-proposals"},updateSelectProposal:function(e,t){e.selected=t,this.setSelectedProposalId(e);var s=a.update({id:e.id},{selected:t});return s.catch(function(s){i.handleErrorResponse(s),e.selected=!t,this.setSelectedProposalId(e)}),s},setSelectedProposalId:function(e){this.setSelectedAnnotation(e,this.selectedProposalIds)},setSeenProposalId:function(e){Vue.set(this.seenProposalIds,e.id,!0)},fetchProposalAnnotations:function(e){return this.proposalAnnotationCache.hasOwnProperty(e)||(this.proposalAnnotationCache[e]=n.getTrainingProposalPoints({jobId:t.id,imageId:e}).then(this.parseAnnotations)),this.proposalAnnotationCache[e]},parseAnnotations:function(e){return Object.keys(e.body).map(function(t){return{id:parseInt(t,10),shape:"Circle",points:e.body[t]}})},setCurrentProposalImageAndAnnotations:function(e){this.currentProposalImage=e[0],this.currentProposals=e[1],this.currentProposalsById={},this.currentProposals.forEach(function(e){this.currentProposalsById[e.id]=e},this)},cacheNextProposalImage:function(){this.currentProposalImageId!==this.nextFocussedProposalImageId&&o.fetchImage(this.nextFocussedProposalImageId).catch(function(){})},cacheNextProposalAnnotations:function(){this.currentProposalImageId!==this.nextFocussedProposalImageId&&this.fetchProposalAnnotations(this.nextFocussedProposalImageId).catch(function(){})},handlePreviousProposalImage:function(){this.currentProposalImageIndex=this.previousProposalImageIndex},handlePreviousProposal:function(){this.previousFocussedProposal?this.focussedProposal=this.previousFocussedProposal:this.handlePreviousProposalImage()},handleNextProposalImage:function(){this.currentProposalImageIndex=this.nextProposalImageIndex},handleNextProposal:function(){this.nextFocussedProposal?this.focussedProposal=this.nextFocussedProposal:this.handleNextProposalImage()},handleRefineProposal:function(e){Vue.Promise.all(e.map(this.updateProposalPoints)).catch(i.handleErrorResponse)},updateProposalPoints:function(e){var t=this.currentProposalsById[e.id];return a.update({id:e.id},{points:e.points}).then(function(){t.points=e.points})},focusProposalToShow:function(){if(this.focussedProposalToShow){var e=this.currentProposalsById[this.focussedProposalToShow.id];e&&this.$refs.refineProposalsCanvas.focusAnnotation(e,!0,!1)}},handleSelectedProposal:function(e,t){e.selected?this.unselectProposal(e):t.shiftKey&&this.lastSelectedProposal?this.selectAllProposalsBetween(e,this.lastSelectedProposal):(this.lastSelectedProposal=e,this.selectProposal(e))},selectAllProposalsBetween:function(e,t){var s=this.proposals.indexOf(e),n=this.proposals.indexOf(t);if(n<s){var a=n;n=s,s=a}for(var i=s+1;i<=n;i++)this.selectProposal(this.proposals[i])},selectProposal:function(e){this.updateSelectProposal(e,!0).then(this.maybeInitFocussedProposal)},unselectProposal:function(e){var t=this.nextFocussedProposal;this.updateSelectProposal(e,!1).bind(this).then(function(){this.maybeUnsetFocussedProposal(e,t)})},maybeInitFocussedProposal:function(){!this.focussedProposal&&this.hasSelectedProposals&&(this.focussedProposal=this.selectedProposals[0])},maybeUnsetFocussedProposal:function(e,t){this.focussedProposal&&this.focussedProposal.id===e.id&&(t&&t.id!==e.id?this.focussedProposal=t:this.focussedProposal=null)},maybeInitCurrentProposalImage:function(){null===this.currentProposalImageIndex&&(this.currentProposalImageIndex=0)},maybeInitCurrentCandidateImage:function(){null===this.currentCandidateImageIndex&&(this.currentCandidateImageIndex=0)},handleLoadingError:function(e){i.danger(e)},setSelectedCandidateId:function(e){this.setSelectedAnnotation(e,this.selectedCandidateIds)},setCandidates:function(e){l=e.body,l.forEach(function(e){c[e.id]=e,this.setSelectedCandidateId(e)},this),this.hasCandidates=l.length>0},fetchCandidates:function(){return this.fetchCandidatesPromise||(this.startLoading(),this.fetchCandidatesPromise=n.getAnnotationCandidates({id:t.id}),this.fetchCandidatesPromise.then(this.setCandidates).catch(i.handleErrorResponse).finally(this.finishLoading)),this.fetchCandidatesPromise},handleSelectedCandidate:function(e){console.log("select",e)},setSelectedAnnotation:function(e,t){e.selected?Vue.set(t,e.id,!0):Vue.delete(t,e.id)},fetchCandidateAnnotations:function(e){return this.candidateAnnotationCache.hasOwnProperty(e)||(this.candidateAnnotationCache[e]=n.getAnnotationCandidatePoints({jobId:t.id,imageId:e}).then(this.parseAnnotations)),this.candidateAnnotationCache[e]},setCurrentCandidateImageAndAnnotations:function(e){this.currentCandidateImage=e[0],this.currentCandidates=e[1],this.currentCandidatesById={},this.currentCandidates.forEach(function(e){this.currentCandidatesById[e.id]=e},this)},handlePreviousCandidateImage:function(){this.currentCandidateImageIndex=this.previousCandidateImageIndex},handleNextCandidateImage:function(){this.currentCandidateImageIndex=this.nextCandidateImageIndex}},watch:{selectProposalsTabOpen:function(e){this.visitedSelectProposalsTab=!0,e&&biigle.$require("keyboard").setActiveSet("select-proposals")},refineProposalsTabOpen:function(e){this.visitedRefineProposalsTab=!0,e&&(biigle.$require("keyboard").setActiveSet("refine-proposals"),this.maybeInitFocussedProposal())},selectCandidatesTabOpen:function(e){this.visitedSelectCandidatesTab=!0,e&&biigle.$require("keyboard").setActiveSet("select-candidates")},refineCandidatesTabOpen:function(e){this.visitedRefineCandidatesTab=!0,e&&biigle.$require("keyboard").setActiveSet("refine-candidates")},visitedSelectProposalsTab:function(){this.fetchProposals()},visitedRefineProposalsTab:function(){this.fetchProposals().then(this.maybeInitFocussedProposal).then(this.maybeInitCurrentProposalImage)},visitedSelectCandidatesTab:function(){this.fetchCandidates()},visitedRefineCandidatesTab:function(){this.fetchCandidates().then(this.maybeInitCurrentCandidateImage)},currentProposalImageId:function(e){e?(this.startLoading(),Vue.Promise.all([o.fetchAndDrawImage(e),this.fetchProposalAnnotations(e),this.fetchProposalsPromise]).then(this.setCurrentProposalImageAndAnnotations).then(this.focusProposalToShow).then(this.cacheNextProposalImage).then(this.cacheNextProposalAnnotations).catch(this.handleLoadingError).finally(this.finishLoading)):this.setCurrentProposalImageAndAnnotations([null,null])},focussedProposalToShow:function(e,t){e&&(t&&t.image_id===e.image_id?this.focusProposalToShow():this.currentProposalImageIndex=this.proposalImageIds.indexOf(e.image_id),this.setSeenProposalId(e))},currentCandidateImageId:function(e){e?(this.startLoading(),Vue.Promise.all([o.fetchAndDrawImage(e),this.fetchCandidateAnnotations(e),this.fetchCandidatesPromise]).then(this.setCurrentCandidateImageAndAnnotations).catch(this.handleLoadingError).finally(this.finishLoading)):this.setCurrentCandidateImageAndAnnotations([null,null])}}})}),biigle.$declare("maia.api.maiaAnnotation",Vue.resource("api/v1/maia-annotations{/id}",{},{getFile:{method:"GET",url:"api/v1/maia-annotations{/id}/file"}})),biigle.$declare("maia.api.maiaJob",Vue.resource("api/v1/maia-jobs{/id}",{},{save:{method:"POST",url:"api/v1/volumes{/id}/maia-jobs"},getTrainingProposals:{method:"GET",url:"api/v1/maia-jobs{/id}/training-proposals"},getTrainingProposalPoints:{method:"GET",url:"api/v1/maia-jobs{/jobId}/images{/imageId}/training-proposals"},getAnnotationCandidates:{method:"GET",url:"api/v1/maia-jobs{/id}/annotation-candidates"},getAnnotationCandidatePoints:{method:"GET",url:"api/v1/maia-jobs{/jobId}/images{/imageId}/annotation-candidates"}})),biigle.$component("maia.components.candidatesImageGrid",{mixins:[biigle.$require("volumes.components.imageGrid")],components:{imageGridImage:biigle.$require("maia.components.candidatesImageGridImage")},props:{selectedCandidateIds:{type:Object,required:!0}}}),biigle.$component("maia.components.candidatesImageGridImage",{mixins:[biigle.$require("volumes.components.imageGridImage")],template:'<figure class="image-grid__image" :class="classObject" :title="title"><div v-if="showIcon" class="image-icon"><i class="fas fa-3x" :class="iconClass"></i></div><img @click="toggleSelect" :src="url || emptyUrl"><div v-if="showAnnotationLink" class="image-buttons"><a :href="showAnnotationLink" target="_blank" class="image-button" title="Show the annotation in the annotation tool"><span class="fa fa-external-link-square-alt" aria-hidden="true"></span></a></div></figure>',computed:{showAnnotationLink:function(){return!1},selected:function(){return this.$parent.selectedCandidateIds.hasOwnProperty(this.image.id)},title:function(){if(this.selectable)return this.selected?"Remove selected label":"Assign selected label"}},methods:{getBlob:function(){return biigle.$require("maia.api.maiaAnnotation").getFile({id:this.image.id})}}}),biigle.$component("maia.components.proposalsImageGrid",{mixins:[biigle.$require("volumes.components.imageGrid")],components:{imageGridImage:biigle.$require("maia.components.proposalsImageGridImage")},props:{selectedProposalIds:{type:Object,required:!0}}}),biigle.$component("maia.components.proposalsImageGridImage",{mixins:[biigle.$require("volumes.components.imageGridImage")],template:'<figure class="image-grid__image" :class="classObject" :title="title"><div v-if="showIcon" class="image-icon"><i class="fas fa-3x" :class="iconClass"></i></div><img @click="toggleSelect" :src="url || emptyUrl"><div v-if="showAnnotationLink" class="image-buttons"><a :href="showAnnotationLink" target="_blank" class="image-button" title="Show the annotation in the annotation tool"><span class="fa fa-external-link-square-alt" aria-hidden="true"></span></a></div></figure>',computed:{showAnnotationLink:function(){return!1},selected:function(){return this.$parent.selectedProposalIds.hasOwnProperty(this.image.id)},title:function(){if(this.selectable)return this.selected?"Unselect as interesting":"Select as interesting"}},methods:{getBlob:function(){return biigle.$require("maia.api.maiaAnnotation").getFile({id:this.image.id})}}}),biigle.$component("maia.components.refineCandidatesTab",{props:{},data:function(){return{}},computed:{},methods:{},created:function(){}}),biigle.$component("maia.components.refineProposalsCanvas",{mixins:[biigle.$require("annotations.components.annotationCanvas")],props:{unselectedAnnotations:{type:Array,default:function(){return[]}}},data:function(){return{selectingProposal:!1}},computed:{hasAnnotations:function(){return this.annotations.length>0}},methods:{handlePreviousImage:function(e){this.$emit("previous-image")},handleNextImage:function(e){this.$emit("next-image")},toggleMarkAsInteresting:function(){this.selectingProposal=!this.selectingProposal},createUnselectedAnnotationsLayer:function(){this.unselectedAnnotationFeatures=new ol.Collection,this.unselectedAnnotationSource=new ol.source.Vector({features:this.unselectedAnnotationFeatures}),this.unselectedAnnotationLayer=new ol.layer.Vector({source:this.unselectedAnnotationSource,zIndex:50,updateWhileAnimating:!0,updateWhileInteracting:!0,style:biigle.$require("annotations.stores.styles").editing,opacity:.5})},createSelectProposalInteraction:function(e){var t=biigle.$require("annotations.ol.AttachLabelInteraction");this.selectProposalInteraction=new t({map:this.map,features:e}),this.selectProposalInteraction.setActive(!1),this.selectProposalInteraction.on("attach",this.handleSelectProposal)},handleSelectProposal:function(e){this.$emit("select",e.feature.get("annotation"))},handleUnselectProposal:function(){this.selectedAnnotations.length>0&&this.$emit("unselect",this.selectedAnnotations[0])}},watch:{unselectedAnnotations:function(e){this.refreshAnnotationSource(e,this.unselectedAnnotationSource)},selectingProposal:function(e){this.selectProposalInteraction.setActive(e)}},created:function(){this.createUnselectedAnnotationsLayer(),this.map.addLayer(this.unselectedAnnotationLayer),this.selectInteraction.setActive(!1),this.canModify&&(this.createSelectProposalInteraction(this.unselectedAnnotationFeatures),this.map.addInteraction(this.selectProposalInteraction),biigle.$require("keyboard").on("Delete",this.handleUnselectProposal,0,this.listenerSet))}}),biigle.$component("maia.components.refineProposalsTab",{props:{selectedProposals:{type:Array,required:!0},seenProposals:{type:Array,required:!0}},computed:{numberSelectedProposals:function(){return this.selectedProposals.length},numberSeenProposals:function(){return this.seenProposals.length},hasNoSelectedTp:function(){return 0===this.numberSelectedProposals},hasSeenAllSelectedProposals:function(){return this.numberSelectedProposals>0&&this.numberSelectedProposals===this.numberSeenProposals},textClass:function(){return this.hasSeenAllSelectedProposals?"text-success":""},buttonClass:function(){return this.hasSeenAllSelectedProposals?"btn-success":"btn-default"}}}),biigle.$component("maia.components.selectCandidatesTab",{props:{},data:function(){return{}},computed:{},methods:{},created:function(){}}),biigle.$component("maia.components.selectProposalsTab",{props:{proposals:{type:Array,required:!0},selectedProposals:{type:Array,required:!0}},data:function(){return{}},computed:{selectedProposalsCount:function(){return this.selectedProposals.length},proposalsCount:function(){return this.proposals.length}},methods:{proceed:function(){this.$emit("proceed")}},created:function(){}});
biigle.$viewModel("maia-job-form",function(e){new Vue({el:e,data:{showAdvanced:!1},methods:{toggle:function(){this.showAdvanced=!this.showAdvanced}}})}),biigle.$viewModel("maia-show-container",function(e){var t=biigle.$require("maia.job"),n=biigle.$require("maia.states"),a=biigle.$require("maia.labelTrees"),i=biigle.$require("maia.api.maiaJob"),s=biigle.$require("maia.api.trainingProposal"),o=biigle.$require("maia.api.annotationCandidate"),d=biigle.$require("messages.store"),r=biigle.$require("annotations.stores.images"),l=[],c={},u=[],h={};new Vue({el:e,mixins:[biigle.$require("core.mixins.loader")],components:{sidebar:biigle.$require("annotations.components.sidebar"),sidebarTab:biigle.$require("core.components.sidebarTab"),selectProposalsTab:biigle.$require("maia.components.selectProposalsTab"),proposalsImageGrid:biigle.$require("maia.components.proposalsImageGrid"),refineProposalsTab:biigle.$require("maia.components.refineProposalsTab"),refineCanvas:biigle.$require("maia.components.refineCanvas"),selectCandidatesTab:biigle.$require("maia.components.selectCandidatesTab"),candidatesImageGrid:biigle.$require("maia.components.candidatesImageGrid"),refineCandidatesTab:biigle.$require("maia.components.refineCandidatesTab")},data:{visitedSelectProposalsTab:!1,visitedRefineProposalsTab:!1,visitedSelectCandidatesTab:!1,visitedRefineCandidatesTab:!1,openTab:"info",labelTrees:a,fetchProposalsPromise:null,hasProposals:!1,selectedProposalIds:{},seenProposalIds:{},lastSelectedProposal:null,currentProposalImage:null,currentProposalImageIndex:null,currentProposals:[],currentProposalsById:{},focussedProposal:null,proposalAnnotationCache:{},fetchCandidatesPromise:null,hasCandidates:!1,selectedCandidateIds:{},convertedCandidateIds:{},lastSelectedCandidate:null,currentCandidateImage:null,currentCandidateImageIndex:null,currentCandidates:[],currentCandidatesById:{},focussedCandidate:null,candidateAnnotationCache:{},selectedLabel:null},computed:{infoTabOpen:function(){return"info"===this.openTab},selectProposalsTabOpen:function(){return"select-proposals"===this.openTab},refineProposalsTabOpen:function(){return"refine-proposals"===this.openTab},selectCandidatesTabOpen:function(){return"select-candidates"===this.openTab},refineCandidatesTabOpen:function(){return"refine-candidates"===this.openTab},isInTrainingProposalState:function(){return t.state_id===n["training-proposals"]},isInAnnotationCandidateState:function(){return t.state_id===n["annotation-candidates"]},proposals:function(){return this.hasProposals?l:[]},selectedProposals:function(){return Object.keys(this.selectedProposalIds).map(function(e){return c[e]})},proposalsForSelectView:function(){return this.isInTrainingProposalState?this.proposals:this.selectedProposals},hasSelectedProposals:function(){return this.selectedProposals.length>0},proposalImageIds:function(){var e={};return this.proposals.forEach(function(t){e[t.image_id]=void 0}),Object.keys(e).map(function(e){return parseInt(e,10)})},currentProposalImageId:function(){return this.proposalImageIds[this.currentProposalImageIndex]},nextProposalImageIndex:function(){return(this.currentProposalImageIndex+1)%this.proposalImageIds.length},nextProposalImageId:function(){return this.proposalImageIds[this.nextProposalImageIndex]},nextFocussedProposalImageId:function(){return this.nextFocussedProposal?this.nextFocussedProposal.image_id:this.nextProposalImageId},previousProposalImageIndex:function(){return(this.currentProposalImageIndex-1+this.proposalImageIds.length)%this.proposalImageIds.length},previousProposalImageId:function(){return this.proposalImageIds[this.previousProposalImageIndex]},hasCurrentProposalImage:function(){return null!==this.currentProposalImage},currentSelectedProposals:function(){return this.currentProposals.filter(function(e){return this.selectedProposalIds.hasOwnProperty(e.id)},this)},currentUnselectedProposals:function(){return this.currentProposals.filter(function(e){return!this.selectedProposalIds.hasOwnProperty(e.id)},this)},previousFocussedProposal:function(){var e=(this.selectedProposals.indexOf(this.focussedProposal)-1+this.selectedProposals.length)%this.selectedProposals.length;return this.selectedProposals[e]},nextFocussedProposal:function(){var e=(this.selectedProposals.indexOf(this.focussedProposal)+1)%this.selectedProposals.length;return this.selectedProposals[e]},focussedProposalToShow:function(){return this.refineProposalsTabOpen?this.focussedProposal:null},focussedProposalArray:function(){return this.focussedProposalToShow?this.currentSelectedProposals.filter(function(e){return e.id===this.focussedProposalToShow.id},this):[]},selectedAndSeenProposals:function(){return this.selectedProposals.filter(function(e){return this.seenProposalIds.hasOwnProperty(e.id)},this)},candidates:function(){return this.hasCandidates?u:[]},selectedCandidates:function(){return Object.keys(this.selectedCandidateIds).map(function(e){return h[e]})},hasSelectedCandidates:function(){return this.selectedCandidates.length>0},candidateImageIds:function(){var e={};return this.candidates.forEach(function(t){e[t.image_id]=void 0}),Object.keys(e).map(function(e){return parseInt(e,10)})},currentCandidateImageId:function(){return this.candidateImageIds[this.currentCandidateImageIndex]},nextCandidateImageIndex:function(){return(this.currentCandidateImageIndex+1)%this.candidateImageIds.length},nextCandidateImageId:function(){return this.candidateImageIds[this.nextCandidateImageIndex]},previousCandidateImageIndex:function(){return(this.currentCandidateImageIndex-1+this.candidateImageIds.length)%this.candidateImageIds.length},previousCandidateImageId:function(){return this.candidateImageIds[this.previousCandidateImageIndex]},hasCurrentCandidateImage:function(){return null!==this.currentCandidateImage},currentSelectedCandidates:function(){return this.currentCandidates.filter(function(e){return this.selectedCandidateIds.hasOwnProperty(e.id)},this)},currentUnselectedCandidates:function(){return this.currentCandidates.filter(function(e){return!this.selectedCandidateIds.hasOwnProperty(e.id)},this)},previousFocussedCandidate:function(){var e=(this.selectedCandidates.indexOf(this.focussedCandidate)-1+this.selectedCandidates.length)%this.selectedCandidates.length;return this.selectedCandidates[e]},nextFocussedCandidate:function(){var e=(this.selectedCandidates.indexOf(this.focussedCandidate)+1)%this.selectedCandidates.length;return this.selectedCandidates[e]},nextFocussedCandidateImageId:function(){return this.nextFocussedCandidate?this.nextFocussedCandidate.image_id:this.nextCandidateImageId},focussedCandidateToShow:function(){return this.refineCandidatesTabOpen?this.focussedCandidate:null},focussedCandidateArray:function(){return this.focussedCandidateToShow?this.currentSelectedCandidates.filter(function(e){return e.id===this.focussedCandidateToShow.id},this):[]}},methods:{handleSidebarToggle:function(){this.$nextTick(function(){this.$refs.proposalsImageGrid&&this.$refs.proposalsImageGrid.$emit("resize"),this.$refs.candidatesImageGrid&&this.$refs.candidatesImageGrid.$emit("resize")})},handleTabOpened:function(e){this.openTab=e},setProposals:function(e){l=e.body,l.forEach(function(e){c[e.id]=e,this.setSelectedProposalId(e)},this),this.hasProposals=l.length>0},fetchProposals:function(){return this.fetchProposalsPromise||(this.startLoading(),this.fetchProposalsPromise=i.getTrainingProposals({id:t.id}),this.fetchProposalsPromise.then(this.setProposals,d.handleErrorResponse).finally(this.finishLoading)),this.fetchProposalsPromise},openRefineProposalsTab:function(){this.openTab="refine-proposals"},openRefineCandidatesTab:function(){this.openTab="refine-candidates"},updateSelectProposal:function(e,t){e.selected=t,this.setSelectedProposalId(e);var n=s.update({id:e.id},{selected:t});return n.catch(function(n){d.handleErrorResponse(n),e.selected=!t,this.setSelectedProposalId(e)}),n},setSelectedProposalId:function(e){e.selected?Vue.set(this.selectedProposalIds,e.id,!0):Vue.delete(this.selectedProposalIds,e.id)},setSeenProposalId:function(e){Vue.set(this.seenProposalIds,e.id,!0)},fetchProposalAnnotations:function(e){return this.proposalAnnotationCache.hasOwnProperty(e)||(this.proposalAnnotationCache[e]=i.getTrainingProposalPoints({jobId:t.id,imageId:e}).then(this.parseAnnotations)),this.proposalAnnotationCache[e]},parseAnnotations:function(e){return Object.keys(e.body).map(function(t){return{id:parseInt(t,10),shape:"Circle",points:e.body[t]}})},setCurrentProposalImageAndAnnotations:function(e){this.currentProposalImage=e[0],this.currentProposals=e[1],this.currentProposalsById={},this.currentProposals.forEach(function(e){this.currentProposalsById[e.id]=e},this)},cacheNextProposalImage:function(){this.currentProposalImageId!==this.nextFocussedProposalImageId&&r.fetchImage(this.nextFocussedProposalImageId).catch(function(){})},cacheNextProposalAnnotations:function(){this.currentProposalImageId!==this.nextFocussedProposalImageId&&this.fetchProposalAnnotations(this.nextFocussedProposalImageId).catch(function(){})},handlePreviousProposalImage:function(){this.currentProposalImageIndex=this.previousProposalImageIndex},handlePreviousProposal:function(){this.previousFocussedProposal?this.focussedProposal=this.previousFocussedProposal:this.handlePreviousProposalImage()},handleNextProposalImage:function(){this.currentProposalImageIndex=this.nextProposalImageIndex},handleNextProposal:function(){this.nextFocussedProposal?this.focussedProposal=this.nextFocussedProposal:this.handleNextProposalImage()},handleRefineProposal:function(e){Vue.Promise.all(e.map(this.updateProposalPoints)).catch(d.handleErrorResponse)},updateProposalPoints:function(e){var t=this.currentProposalsById[e.id];return s.update({id:e.id},{points:e.points}).then(function(){t.points=e.points})},focusProposalToShow:function(){if(this.focussedProposalToShow){var e=this.currentProposalsById[this.focussedProposalToShow.id];e&&this.$refs.refineProposalsCanvas.focusAnnotation(e,!0,!1)}},handleSelectedProposal:function(e,t){e.selected?this.unselectProposal(e):t.shiftKey&&this.lastSelectedProposal?this.doForEachBetween(this.proposals,e,this.lastSelectedProposal,this.selectProposal):(this.lastSelectedProposal=e,this.selectProposal(e))},selectProposal:function(e){this.updateSelectProposal(e,!0).then(this.maybeInitFocussedProposal)},unselectProposal:function(e){var t=this.nextFocussedProposal;this.updateSelectProposal(e,!1).bind(this).then(function(){this.maybeUnsetFocussedProposal(e,t)})},maybeInitFocussedProposal:function(){!this.focussedProposal&&this.hasSelectedProposals&&(this.focussedProposal=this.selectedProposals[0])},maybeUnsetFocussedProposal:function(e,t){this.focussedProposal&&this.focussedProposal.id===e.id&&(t&&t.id!==e.id?this.focussedProposal=t:this.focussedProposal=null)},maybeInitCurrentProposalImage:function(){null===this.currentProposalImageIndex&&(this.currentProposalImageIndex=0)},maybeInitCurrentCandidateImage:function(){null===this.currentCandidateImageIndex&&(this.currentCandidateImageIndex=0)},handleLoadingError:function(e){d.danger(e)},setSelectedCandidateId:function(e){e.label&&!e.annotation_id?Vue.set(this.selectedCandidateIds,e.id,e.label):Vue.delete(this.selectedCandidateIds,e.id)},setConvertedCandidateId:function(e){e.annotation_id?Vue.set(this.convertedCandidateIds,e.id,e.annotation_id):Vue.delete(this.convertedCandidateIds,e.id)},setCandidates:function(e){u=e.body,u.forEach(function(e){h[e.id]=e,this.setSelectedCandidateId(e),this.setConvertedCandidateId(e)},this),this.hasCandidates=u.length>0},fetchCandidates:function(){return this.fetchCandidatesPromise||(this.startLoading(),this.fetchCandidatesPromise=i.getAnnotationCandidates({id:t.id}),this.fetchCandidatesPromise.then(this.setCandidates,d.handleErrorResponse).finally(this.finishLoading)),this.fetchCandidatesPromise},handleSelectedCandidate:function(e,t){e.label?this.unselectCandidate(e):t.shiftKey&&this.lastSelectedCandidate&&this.selectedLabel?this.doForEachBetween(this.candidates,e,this.lastSelectedCandidate,this.selectCandidate):(this.lastSelectedCandidate=e,this.selectCandidate(e))},selectCandidate:function(e){this.selectedLabel?e.annotation_id||this.updateSelectCandidate(e,this.selectedLabel).then(this.maybeInitFocussedCandidate):d.info("Please select a label first.")},unselectCandidate:function(e){var t=this.nextFocussedCandidate;this.updateSelectCandidate(e,null).bind(this).then(function(){this.maybeUnsetFocussedCandidate(e,t)})},updateSelectCandidate:function(e,t){var n=e.label;e.label=t,this.setSelectedCandidateId(e);var a=t?t.id:null,i=o.update({id:e.id},{label_id:a});return i.catch(function(t){d.handleErrorResponse(t),e.label=!n,this.setSelectedCandidateId(e)}),i},fetchCandidateAnnotations:function(e){return this.candidateAnnotationCache.hasOwnProperty(e)||(this.candidateAnnotationCache[e]=i.getAnnotationCandidatePoints({jobId:t.id,imageId:e}).then(this.parseAnnotations)),this.candidateAnnotationCache[e]},setCurrentCandidateImageAndAnnotations:function(e){this.currentCandidateImage=e[0],this.currentCandidates=e[1],this.currentCandidatesById={},this.currentCandidates.forEach(function(e){this.currentCandidatesById[e.id]=e},this)},handlePreviousCandidateImage:function(){this.currentCandidateImageIndex=this.previousCandidateImageIndex},handlePreviousCandidate:function(){this.previousFocussedCandidate?this.focussedCandidate=this.previousFocussedCandidate:this.handlePreviousCandidateImage()},handleNextCandidateImage:function(){this.currentCandidateImageIndex=this.nextCandidateImageIndex},handleNextCandidate:function(){this.nextFocussedCandidate?this.focussedCandidate=this.nextFocussedCandidate:this.handleNextCandidateImage()},focusCandidateToShow:function(){if(this.focussedCandidateToShow){var e=this.currentCandidatesById[this.focussedCandidateToShow.id];e&&this.$refs.refineCandidatesCanvas.focusAnnotation(e,!0,!1)}},maybeInitFocussedCandidate:function(){!this.focussedCandidate&&this.hasSelectedCandidates&&(this.focussedCandidate=this.selectedCandidates[0])},maybeUnsetFocussedCandidate:function(e,t){this.focussedCandidate&&this.focussedCandidate.id===e.id&&(t&&t.id!==e.id?this.focussedCandidate=t:this.focussedCandidate=null)},handleSelectedLabel:function(e){this.selectedLabel=e},doForEachBetween:function(e,t,n,a){var i=e.indexOf(t),s=e.indexOf(n);if(s<i){var o=s;s=i,i=o+1}else s-=1;for(var d=i;d<=s;d++)a(e[d])},cacheNextCandidateImage:function(){this.currentCandidateImageId!==this.nextFocussedCandidateImageId&&r.fetchImage(this.nextFocussedCandidateImageId).catch(function(){})},cacheNextCandidateAnnotations:function(){this.currentCandidateImageId!==this.nextFocussedCandidateImageId&&this.fetchCandidateAnnotations(this.nextFocussedCandidateImageId).catch(function(){})},handleRefineCandidate:function(e){Vue.Promise.all(e.map(this.updateCandidatePoints)).catch(d.handleErrorResponse)},updateCandidatePoints:function(e){var t=this.currentCandidatesById[e.id];return o.update({id:e.id},{points:e.points}).then(function(){t.points=e.points})},handleConvertCandidates:function(){this.startLoading(),i.convertAnnotationCandidates({id:t.id},{}).then(this.handleConvertedCandidates,d.handleErrorResponse).finally(this.finishLoading)},handleConvertedCandidates:function(e){Object.keys(e.body).forEach(function(t){var n=this.nextFocussedCandidate,a=h[t];a.annotation_id=e.body[t],this.setSelectedCandidateId(a),this.setConvertedCandidateId(a),this.maybeUnsetFocussedCandidate(a,n)},this)}},watch:{selectProposalsTabOpen:function(e){this.visitedSelectProposalsTab=!0,e&&biigle.$require("keyboard").setActiveSet("select-proposals")},refineProposalsTabOpen:function(e){this.visitedRefineProposalsTab=!0,e&&(biigle.$require("keyboard").setActiveSet("refine-proposals"),this.maybeInitFocussedProposal())},selectCandidatesTabOpen:function(e){this.visitedSelectCandidatesTab=!0,e&&biigle.$require("keyboard").setActiveSet("select-candidates")},refineCandidatesTabOpen:function(e){this.visitedRefineCandidatesTab=!0,e&&biigle.$require("keyboard").setActiveSet("refine-candidates")},visitedSelectProposalsTab:function(){this.fetchProposals()},visitedRefineProposalsTab:function(){this.fetchProposals().then(this.maybeInitFocussedProposal).then(this.maybeInitCurrentProposalImage)},visitedSelectCandidatesTab:function(){this.fetchCandidates()},visitedRefineCandidatesTab:function(){this.fetchCandidates().then(this.maybeInitFocussedCandidate).then(this.maybeInitCurrentCandidateImage)},currentProposalImageId:function(e){e?(this.startLoading(),Vue.Promise.all([r.fetchAndDrawImage(e),this.fetchProposalAnnotations(e),this.fetchProposals()]).then(this.setCurrentProposalImageAndAnnotations).then(this.focusProposalToShow).then(this.cacheNextProposalImage).then(this.cacheNextProposalAnnotations).catch(this.handleLoadingError).finally(this.finishLoading)):this.setCurrentProposalImageAndAnnotations([null,null])},focussedProposalToShow:function(e,t){e&&(t&&t.image_id===e.image_id?this.focusProposalToShow():this.currentProposalImageIndex=this.proposalImageIds.indexOf(e.image_id),this.setSeenProposalId(e))},currentCandidateImageId:function(e){e?(this.startLoading(),Vue.Promise.all([r.fetchAndDrawImage(e),this.fetchCandidateAnnotations(e),this.fetchCandidates()]).then(this.setCurrentCandidateImageAndAnnotations).then(this.focusCandidateToShow).then(this.cacheNextCandidateImage).then(this.cacheNextCandidateAnnotations).catch(this.handleLoadingError).finally(this.finishLoading)):this.setCurrentCandidateImageAndAnnotations([null,null])},focussedCandidateToShow:function(e,t){e&&(t&&t.image_id===e.image_id?this.focusCandidateToShow():this.currentCandidateImageIndex=this.candidateImageIds.indexOf(e.image_id))}}})}),biigle.$declare("maia.api.annotationCandidate",Vue.resource("api/v1/maia/annotation-candidates{/id}",{},{getFile:{method:"GET",url:"api/v1/maia/annotation-candidates{/id}/file"}})),biigle.$declare("maia.api.maiaJob",Vue.resource("api/v1/maia-jobs{/id}",{},{save:{method:"POST",url:"api/v1/volumes{/id}/maia-jobs"},getTrainingProposals:{method:"GET",url:"api/v1/maia-jobs{/id}/training-proposals"},getTrainingProposalPoints:{method:"GET",url:"api/v1/maia-jobs{/jobId}/images{/imageId}/training-proposals"},getAnnotationCandidates:{method:"GET",url:"api/v1/maia-jobs{/id}/annotation-candidates"},getAnnotationCandidatePoints:{method:"GET",url:"api/v1/maia-jobs{/jobId}/images{/imageId}/annotation-candidates"},convertAnnotationCandidates:{method:"POST",url:"api/v1/maia-jobs{/id}/annotation-candidates"}})),biigle.$declare("maia.api.trainingProposal",Vue.resource("api/v1/maia/training-proposals{/id}",{},{getFile:{method:"GET",url:"api/v1/maia/training-proposals{/id}/file"}})),biigle.$component("maia.components.candidatesImageGrid",{mixins:[biigle.$require("volumes.components.imageGrid")],components:{imageGridImage:biigle.$require("maia.components.candidatesImageGridImage")},props:{selectedCandidateIds:{type:Object,required:!0},convertedCandidateIds:{type:Object,required:!0}}}),biigle.$component("maia.components.candidatesImageGridImage",{mixins:[biigle.$require("volumes.components.imageGridImage")],template:'<figure class="image-grid__image image-grid__image--annotation-candidate" :class="classObject" :title="title"><div v-if="showIcon" class="image-icon"><i class="fas" :class="iconClass"></i></div><img @click="toggleSelect" :src="url || emptyUrl"><div v-if="showAnnotationLink" class="image-buttons"><a :href="showAnnotationLink" target="_blank" class="image-button" title="Show the annotation in the annotation tool"><span class="fa fa-external-link-square-alt" aria-hidden="true"></span></a></div><div v-if="selected" class="attached-label"><span class="attached-label__color" :style="labelStyle"></span> <span class="attached-label__name" v-text="label.name"></span></div></figure>',computed:{showAnnotationLink:function(){return!1},label:function(){return this.selected?this.$parent.selectedCandidateIds[this.image.id]:null},selected:function(){return this.$parent.selectedCandidateIds.hasOwnProperty(this.image.id)},converted:function(){return this.$parent.convertedCandidateIds.hasOwnProperty(this.image.id)},selectable:function(){return!this.converted},classObject:function(){return{"image-grid__image--selected":this.selected||this.converted,"image-grid__image--selectable":this.selectable,"image-grid__image--fade":this.selectedFade,"image-grid__image--small-icon":this.smallIcon}},iconClass:function(){return this.converted?"fa-lock":"fa-"+this.selectedIcon},showIcon:function(){return this.selectable||this.selected||this.converted},title:function(){return this.converted?"This annotation candidate has been converted":this.selected?"Detach label":"Attach selected label"},labelStyle:function(){return{"background-color":"#"+this.label.color}}},methods:{getBlob:function(){return biigle.$require("maia.api.annotationCandidate").getFile({id:this.image.id})}}}),biigle.$component("maia.components.proposalsImageGrid",{mixins:[biigle.$require("volumes.components.imageGrid")],components:{imageGridImage:biigle.$require("maia.components.proposalsImageGridImage")},props:{selectedProposalIds:{type:Object,required:!0}}}),biigle.$component("maia.components.proposalsImageGridImage",{mixins:[biigle.$require("volumes.components.imageGridImage")],template:'<figure class="image-grid__image" :class="classObject" :title="title"><div v-if="showIcon" class="image-icon"><i class="fas" :class="iconClass"></i></div><img @click="toggleSelect" :src="url || emptyUrl"><div v-if="showAnnotationLink" class="image-buttons"><a :href="showAnnotationLink" target="_blank" class="image-button" title="Show the annotation in the annotation tool"><span class="fa fa-external-link-square-alt" aria-hidden="true"></span></a></div></figure>',computed:{showAnnotationLink:function(){return!1},selected:function(){return this.$parent.selectedProposalIds.hasOwnProperty(this.image.id)},title:function(){if(this.selectable)return this.selected?"Unselect as interesting":"Select as interesting"},smallIcon:function(){return!this.selectable},selectedFade:function(){return this.selectable}},methods:{getBlob:function(){return biigle.$require("maia.api.trainingProposal").getFile({id:this.image.id})}}}),biigle.$component("maia.components.refineCandidatesTab",{components:{labelTrees:biigle.$require("labelTrees.components.labelTrees")},props:{selectedCandidates:{type:Array,required:!0},labelTrees:{type:Array,required:!0}},data:function(){return{}},computed:{hasNoSelectedCandidates:function(){return 0===this.selectedCandidates.length}},methods:{handleSelectedLabel:function(e){this.$emit("select",e)},handleDeselectedLabel:function(e){this.$emit("select",null)},handleConvertCandidates:function(e){this.hasNoSelectedCandidates||this.$emit("convert")}},created:function(){}}),biigle.$component("maia.components.refineCanvas",{mixins:[biigle.$require("annotations.components.annotationCanvas")],props:{unselectedAnnotations:{type:Array,default:function(){return[]}}},data:function(){return{selectingMaiaAnnotation:!1}},computed:{hasAnnotations:function(){return this.annotations.length>0}},methods:{handlePreviousImage:function(e){this.$emit("previous-image")},handleNextImage:function(e){this.$emit("next-image")},toggleSelectingMaiaAnnotation:function(){this.selectingMaiaAnnotation=!this.selectingMaiaAnnotation},createUnselectedAnnotationsLayer:function(){this.unselectedAnnotationFeatures=new ol.Collection,this.unselectedAnnotationSource=new ol.source.Vector({features:this.unselectedAnnotationFeatures}),this.unselectedAnnotationLayer=new ol.layer.Vector({source:this.unselectedAnnotationSource,zIndex:50,updateWhileAnimating:!0,updateWhileInteracting:!0,style:biigle.$require("annotations.stores.styles").editing,opacity:.5})},createSelectMaiaAnnotationInteraction:function(e){var t=biigle.$require("annotations.ol.AttachLabelInteraction");this.selectMaiaAnnotationInteraction=new t({map:this.map,features:e}),this.selectMaiaAnnotationInteraction.setActive(!1),this.selectMaiaAnnotationInteraction.on("attach",this.handleSelectMaiaAnnotation)},handleSelectMaiaAnnotation:function(e){this.$emit("select",e.feature.get("annotation"))},handleUnselectMaiaAnnotation:function(){this.selectedAnnotations.length>0&&this.$emit("unselect",this.selectedAnnotations[0])}},watch:{unselectedAnnotations:function(e){this.refreshAnnotationSource(e,this.unselectedAnnotationSource)},selectingMaiaAnnotation:function(e){this.selectMaiaAnnotationInteraction.setActive(e)}},created:function(){this.createUnselectedAnnotationsLayer(),this.map.addLayer(this.unselectedAnnotationLayer),this.selectInteraction.setActive(!1),this.canModify&&(this.createSelectMaiaAnnotationInteraction(this.unselectedAnnotationFeatures),this.map.addInteraction(this.selectMaiaAnnotationInteraction),biigle.$require("keyboard").on("Delete",this.handleUnselectMaiaAnnotation,0,this.listenerSet))}}),biigle.$component("maia.components.refineProposalsTab",{props:{selectedProposals:{type:Array,required:!0},seenProposals:{type:Array,required:!0}},computed:{numberSelectedProposals:function(){return this.selectedProposals.length},numberSeenProposals:function(){return this.seenProposals.length},hasNoSelectedProposals:function(){return 0===this.numberSelectedProposals},hasSeenAllSelectedProposals:function(){return this.numberSelectedProposals>0&&this.numberSelectedProposals===this.numberSeenProposals},textClass:function(){return this.hasSeenAllSelectedProposals?"text-success":""},buttonClass:function(){return this.hasSeenAllSelectedProposals?"btn-success":"btn-default"}}}),biigle.$component("maia.components.selectCandidatesTab",{components:{labelTrees:biigle.$require("labelTrees.components.labelTrees")},props:{labelTrees:{type:Array,required:!0}},data:function(){return{}},computed:{},methods:{handleSelectedLabel:function(e){this.$emit("select",e)},handleDeselectedLabel:function(e){this.$emit("select",null)},proceed:function(){this.$emit("proceed")}},created:function(){}}),biigle.$component("maia.components.selectProposalsTab",{props:{proposals:{type:Array,required:!0},selectedProposals:{type:Array,required:!0}},data:function(){return{}},computed:{selectedProposalsCount:function(){return this.selectedProposals.length},proposalsCount:function(){return this.proposals.length}},methods:{proceed:function(){this.$emit("proceed")}},created:function(){}});
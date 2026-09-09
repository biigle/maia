<?php

namespace Biigle\Modules\Maia;

use Biigle\Traits\EnumSerialization;

/**
 * Represents the different stages of a Maia job.
 */
enum MaiaJobState: int implements \JsonSerializable
{
    use EnumSerialization;

    // The novelty detection stage.
    case NOVELTY_DETECTION = 1;
    // A failure during novelty detection.
    case FAILED_NOVELTY_DETECTION = 2;
    // The manual selection and refinement of training proposals stage.
    case TRAINING_PROPOSALS = 3;
    // The manual review of annotation candidates stage.
    case ANNOTATION_CANDIDATES = 4;
    // The object detection stage.
    // NOTE: This was incorrectly called instance segmentation before
    case OBJECT_DETECTION = 5;
    // A failure during object detection.
    // NOTE: This was incorrectly called instance segmentation before
    case FAILED_OBJECT_DETECTION = 6;

    public static function noveltyDetection(): self
    {
        return self::NOVELTY_DETECTION;
    }

    public static function failedNoveltyDetection(): self
    {
        return self::FAILED_NOVELTY_DETECTION;
    }

    public static function trainingProposals(): self
    {
        return self::TRAINING_PROPOSALS;
    }

    public static function annotationCandidates(): self
    {
        return self::ANNOTATION_CANDIDATES;
    }

    public static function objectDetection(): self
    {
        return self::OBJECT_DETECTION;
    }

    public static function failedObjectDetection(): self
    {
        return self::FAILED_OBJECT_DETECTION;
    }

    public static function noveltyDetectionId(): int
    {
        return self::NOVELTY_DETECTION->value;
    }

    public static function failedNoveltyDetectionId(): int
    {
        return self::FAILED_NOVELTY_DETECTION->value;
    }

    public static function trainingProposalsId(): int
    {
        return self::TRAINING_PROPOSALS->value;
    }

    public static function annotationCandidatesId(): int
    {
        return self::ANNOTATION_CANDIDATES->value;
    }

    public static function objectDetectionId(): int
    {
        return self::OBJECT_DETECTION->value;
    }

    public static function failedObjectDetectionId(): int
    {
        return self::FAILED_OBJECT_DETECTION->value;
    }

    public function label(): string
    {
        return match ($this) {
            self::NOVELTY_DETECTION => 'novelty-detection',
            self::FAILED_NOVELTY_DETECTION => 'failed-novelty-detection',
            self::TRAINING_PROPOSALS => 'training-proposals',
            self::ANNOTATION_CANDIDATES => 'annotation-candidates',
            self::OBJECT_DETECTION => 'instance-segmentation',
            self::FAILED_OBJECT_DETECTION => 'failed-instance-segmentation',
        };
    }
}


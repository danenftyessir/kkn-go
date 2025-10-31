-- Fix missing 'stories' and 'interests' columns in PostgreSQL/Supabase
-- Run this script in your Supabase SQL Editor

-- Step 1: Check if 'story' column exists (from old migration)
DO $$
BEGIN
    -- If 'story' column exists, rename it to 'stories'
    IF EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name = 'students' AND column_name = 'story'
    ) THEN
        ALTER TABLE students RENAME COLUMN story TO stories;
        RAISE NOTICE 'Renamed column story to stories';
    END IF;
END $$;

-- Step 2: Check if 'stories' column exists, if not create it
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name = 'students' AND column_name = 'stories'
    ) THEN
        ALTER TABLE students ADD COLUMN stories JSON NULL;
        RAISE NOTICE 'Created stories column';
    ELSE
        -- If it exists but is TEXT, change to JSON
        IF EXISTS (
            SELECT 1 FROM information_schema.columns
            WHERE table_name = 'students'
            AND column_name = 'stories'
            AND data_type = 'text'
        ) THEN
            ALTER TABLE students ALTER COLUMN stories TYPE JSON USING stories::json;
            RAISE NOTICE 'Changed stories column to JSON';
        END IF;
    END IF;
END $$;

-- Step 3: Check if 'interests' column exists, if not create it
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name = 'students' AND column_name = 'interests'
    ) THEN
        ALTER TABLE students ADD COLUMN interests JSON NULL;
        RAISE NOTICE 'Created interests column';
    ELSE
        RAISE NOTICE 'Column interests already exists';
    END IF;
END $$;

-- Step 4: Verify the columns exist
SELECT
    column_name,
    data_type,
    is_nullable
FROM information_schema.columns
WHERE table_name = 'students'
AND column_name IN ('stories', 'interests', 'skills')
ORDER BY column_name;
